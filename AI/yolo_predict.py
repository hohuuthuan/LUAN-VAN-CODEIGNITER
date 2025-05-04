from flask import Flask, request, jsonify, send_file
import torch
import numpy as np
import cv2
from ultralytics import YOLO
import os
from datetime import datetime

app = Flask(__name__)

# Load YOLOv8 model
model = YOLO("weights/best100.pt")

@app.route('/predict', methods=['POST'])
def predict():
    try:
        if 'image' not in request.files:
            return jsonify({'error': 'No image provided'}), 400

        file = request.files['image']
        img = np.frombuffer(file.read(), np.uint8)
        img = cv2.imdecode(img, cv2.IMREAD_COLOR)
        
        # Perform inference
        results = model(img)
        
        # Process results and draw bounding boxes
        predictions = []
        for result in results:
            for box in result.boxes:
                confidence = box.conf[0].item()
                if confidence >= 0.1:
                    x1, y1, x2, y2 = map(int, box.xyxy[0].tolist())
                    label = f"{box.cls[0].item()} {confidence:.2f}"
                    
                    # Calculate text size and position
                    font_scale = 0.5
                    thickness = 1
                    (text_width, text_height), baseline = cv2.getTextSize(label, cv2.FONT_HERSHEY_SIMPLEX, font_scale, thickness)
                    text_y = y1 - 10 if y1 - 10 > text_height else y1 + text_height + 10
                    
                    cv2.rectangle(img, (x1, y1), (x2, y2), (0, 255, 0), 2)
                    cv2.rectangle(img, (x1, text_y - text_height - baseline), (x1 + text_width, text_y + baseline), (0, 255, 0), cv2.FILLED)
                    cv2.putText(img, label, (x1, text_y), cv2.FONT_HERSHEY_SIMPLEX, font_scale, (0, 0, 0), thickness)
                    
                    predictions.append({
                        'label': box.cls[0].item(),
                        'confidence': confidence,
                        'bbox': [x1, y1, x2, y2]
                    })
        
        if not predictions:
            return jsonify({'message': 'Không nhận diện được'})
        
        # Save the result image
        timestamp = datetime.now().strftime('%Y%m%d%H%M%S')
        output_path = f"static/predictions/prediction_{timestamp}.jpg"
        os.makedirs(os.path.dirname(output_path), exist_ok=True)
        cv2.imwrite(output_path, img)

        return jsonify({'image_url': output_path, 'predictions': predictions})
    except Exception as e:
        return jsonify({'error': str(e)}), 500

@app.route('/static/predictions/<filename>', methods=['GET'])
def get_image(filename):
    return send_file(f"static/predictions/{filename}", mimetype='image/jpeg')

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000)