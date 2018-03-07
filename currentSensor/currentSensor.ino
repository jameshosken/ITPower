#include <WiFi101.h>
WiFiServer server(80);
void setup()
{
  Serial.begin(115200);
  Serial.println();

  WiFi.begin("", "");

  Serial.print("Connecting");
  while (WiFi.status() != WL_CONNECTED)
  {
    delay(500);
    Serial.print(".");
  }
  Serial.println();
  server.begin();
  Serial.print("Connected, IP address: ");
  Serial.println(WiFi.localIP());
}

void loop() {
  WiFiClient client = server.available();
  while(client.connected()) {
    if(client.available()) {
      String request = client.readStringUntil('\n');
      Serial.println(request);
      if(request.length() <= 2) {
      String response = makeResponse();
      client.println(response);
        delay(10);
        if(client.connected()) {
          client.stop();
        }
      }
    }
  }
}

String makeResponse() {
  String result = "HTTP/1.1 200 OK\n";
  result += "Content-Type: text/html\n\n";
  result += "<!doctype html>\n";
  result += "<html><head><title>";
  result += "Hello from Arduino</title></head>";
  result += "\n<body>\n";

  //output the value of 
  result += analogRead(A0);

  result+= "</body></html>";
  return result;
}

