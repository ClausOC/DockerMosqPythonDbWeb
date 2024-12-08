FROM python:3.9-alpine

# Installér nødvendige afhængigheder
RUN apk add --no-cache gcc musl-dev linux-headers

# Installer python pakker
RUN pip install paho-mqtt mysql-connector-python debugpy

COPY ./scripts /scripts
WORKDIR /scripts

CMD ["python", "-m", "debugpy", "--listen", "0.0.0.0:5678", "mqtt_listener.py"]
