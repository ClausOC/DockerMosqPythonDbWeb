import paho.mqtt.client as mqtt
import mysql.connector
import os

MQTT_BROKER = os.getenv('MQTT_BROKER')
MQTT_PORT = int(os.getenv('MQTT_PORT'))
MQTT_TOPIC = os.getenv('MQTT_TOPIC')

MYSQL_HOST = os.getenv('MYSQL_HOST')
MYSQL_USER = os.getenv('MYSQL_USER')
MYSQL_PASSWORD = os.getenv('MYSQL_PASSWORD')
MYSQL_DB = os.getenv('MYSQL_DB')

def on_connect(client, userdata, flags, rc):
    print("Connected with result code " + str(rc))
    client.subscribe(MQTT_TOPIC)

def on_message(client, userdata, msg):
    print(f"Message received: {msg.topic} {msg.payload}")
    if msg.topic == MQTT_TOPIC:
        insert_into_db(msg.payload.decode('utf-8'))

def check_and_create_table(connection, table_name):
    cursor = connection.cursor()
    cursor.execute(f"SHOW TABLES LIKE '{table_name}'")
    result = cursor.fetchone()
    if not result:
        # Opret tabel, hvis den ikke eksisterer
        create_table_query = f"""
        CREATE TABLE {table_name} (
            id INT AUTO_INCREMENT PRIMARY KEY,
            json VARCHAR(4096) NOT NULL,
            created TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP(),
            data JSON NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        """
        cursor.execute(create_table_query)
        connection.commit()
    cursor.close()

def insert_into_db(payload):
    print(f"Connecting to database...")
    connection = None
    try:
        connection = mysql.connector.connect(
            host = MYSQL_HOST,
            database = MYSQL_DB,
            user = MYSQL_USER,
            password = MYSQL_PASSWORD, 
            collation = 'utf8mb4_unicode_ci'
        )
        check_and_create_table(connection, 'mqttdata_received')
        
        print(f"Ready to insert payload: {payload}")
        cursor = connection.cursor()
        query = "INSERT INTO mqttdata_received (json, data) VALUES (%s, %s)"
        values = (payload, payload) # payload er en JSON-streng 
        cursor.execute(query, values)
        connection.commit()
        print("Data gemt i databasen")
    except mysql.connector.Error as error:
        print("Fejl ved forbindelse til MariaDB: {}".format(error))
    finally:
        if connection and connection.is_connected():
            cursor.close()
            connection.close()


client = mqtt.Client()
client.username_pw_set("user1", "user1")
client.on_connect = on_connect
client.on_message = on_message

client.connect(MQTT_BROKER, MQTT_PORT, 60)
client.loop_forever()
