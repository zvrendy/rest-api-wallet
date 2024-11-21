import re

def extract_model_info(content):
    models = {}
    current_model = None
    namespace = None
    for line in content.split('\n'):
        if line.strip().startswith('namespace '):
            namespace = line.split('{')[0].split()[-1]
            print(f"Found namespace: {namespace}")  # Debug print
        elif line.strip().startswith('class '):
            current_model = line.split()[1].strip()
            if current_model.endswith('{'):
                current_model = current_model[:-1]
            if namespace:
                current_model = f"{namespace}\\{current_model}"
            models[current_model] = {'properties': [], 'methods': []}
            print(f"Found model: {current_model}")  # Debug print
        elif current_model and line.strip().startswith('@property'):
            prop = line.split()[-1].strip()
            models[current_model]['properties'].append(prop)
            print(f"  Found property: {prop}")  # Debug print
        elif current_model and line.strip().startswith('@method'):
            method = re.search(r'@method.*?\|(.*?)\s', line)
            if method:
                models[current_model]['methods'].append(method.group(1))
                print(f"  Found method: {method.group(1)}")  # Debug print
    return models

def generate_plantuml(models):
    plantuml = "@startuml\n\n"
    for model, details in models.items():
        plantuml += f"class {model} {{\n"
        for prop in details['properties']:
            plantuml += f"  +{prop}\n"
        for method in details['methods']:
            plantuml += f"  +{method}()\n"
        plantuml += "}\n\n"
    plantuml += "@enduml"
    return plantuml

# Read the content of your IDE helper file
try:
    with open('_ide_helper_models.php', 'r') as file:
        content = file.read()
    print(f"Successfully read file. Content length: {len(content)} characters")  # Debug print
except FileNotFoundError:
    print("Error: ide_helper_models.php file not found in the current directory.")
    exit(1)
except Exception as e:
    print(f"Error reading file: {str(e)}")
    exit(1)

# Extract model information
models = extract_model_info(content)

if not models:
    print("No models were extracted. Check if the file content matches the expected format.")
    print("First 500 characters of the file:")
    print(content[:500])
else:
    print(f"Extracted {len(models)} models")

# Generate PlantUML
plantuml_diagram = generate_plantuml(models)

# Save the PlantUML diagram to a file
with open('models_diagram.puml', 'w') as file:
    file.write(plantuml_diagram)

print("PlantUML diagram has been generated and saved as 'models_diagram.puml'")
print(f"Diagram content length: {len(plantuml_diagram)} characters")
