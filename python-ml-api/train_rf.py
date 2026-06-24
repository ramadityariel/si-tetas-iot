import numpy as np
from sklearn.ensemble import RandomForestClassifier
import joblib
import os

# Generate synthetic training data
np.random.seed(42)
num_samples = 2000

# Generate random temperatures between 30.0 and 45.0
temperatures = np.random.uniform(30.0, 45.0, num_samples)
# Generate random humidities between 30.0 and 90.0
humidities = np.random.uniform(30.0, 90.0, num_samples)

X = np.column_stack((temperatures, humidities))
y = []

# Label data using rules:
# Optimal: 36.5 <= temp <= 38.5 AND 50 <= humidity <= 70
# Warning: (35.0 <= temp <= 39.0 AND 40 <= humidity <= 80) but not optimal
# Critical: everything else
for temp, hum in X:
    if (36.5 <= temp <= 38.5) and (50 <= hum <= 70):
        y.append("Optimal")
    elif (35.0 <= temp <= 39.0) and (40 <= hum <= 80):
        y.append("Warning")
    else:
        y.append("Critical")

y = np.array(y)

# Train the Random Forest Classifier
clf = RandomForestClassifier(n_estimators=100, random_state=42)
clf.fit(X, y)

# Save the model
model_path = os.path.join(os.path.dirname(__file__), "sensor_rf_model.joblib")
joblib.dump(clf, model_path)
print(f"Model successfully trained and saved to {model_path}")
