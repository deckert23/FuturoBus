#include <SPI.h>
#include <Ethernet.h>
#include <MFRC522.h>
#include <Servo.h>

#define SDA 4
#define RESET 9
#define ledAmarillo 2
#define ledVerde 6
#define ledRojo 8
#define pitido 7
#define infra 5
#define terminal "San%20Felipe" // aca nombre de la ubicacion del arduino

int pos=0;
Servo myservo;
boolean Abierto =false;

MFRC522 mfrc522(SDA, RESET);



// Enter a MAC address and IP address for your controller below.
// The IP address will be dependent on your local network:
byte mac[] = { 0xDE, 0xAD, 0xBE, 0xEF, 0xFE, 0xED };
IPAddress ip(192,168,1,50);
// Initialize the Ethernet server library
// with the IP address and port you want to use 
// (port 80 is default for HTTP):
EthernetClient client;
// direccion del servidor con pagina php
char server[] = "192.168.1.2";
String codigo;
String nombre;
boolean fin = false;
boolean pregunta = true;

int val1;
int val2;
int val3;
int val4;
//********************************************************************************************************************

void setup(){
            //iniciar puerto serie
            Serial.begin(9600);
            //inicializo servo
            myservo.attach(3);
   
            pinMode(ledRojo,OUTPUT);
            pinMode(ledVerde,OUTPUT);
            pinMode(ledAmarillo,OUTPUT);
            pinMode(pitido,OUTPUT);
            //iniciar spi bus
            SPI.begin();
            delay(1000);
            //iniciar coneccion de red
            Ethernet.begin(mac,ip);
            mfrc522.PCD_Init();// Init MFRC522 card
            Serial.println(F("Identifique su tarjeta"));
            
            //imprimir direccion ip
            //Serial.print("IP: ");
            //Serial.println(Ethernet.localIP());
            }

void loop() {
  myservo.write(0);
            digitalWrite(ledAmarillo,HIGH);
            delay(600);
            digitalWrite(ledAmarillo,LOW);
            delay(600);
            ///////////////////////rfid************************************
            MFRC522::MIFARE_Key key;
            for (byte i = 0; i < 6; i++) key.keyByte[i] = 0xFF;
            //some variables we need
            byte block;
            byte len;
            MFRC522::StatusCode status;
            //-------------------------------------------
            // Look for new cards
            if ( ! mfrc522.PICC_IsNewCardPresent()) {
                return;
                }

            // Select one of the cards
            if ( ! mfrc522.PICC_ReadCardSerial()) {
                return;
                }
            // gusrdo las serie de numero de la tarjeta
            val1=mfrc522.uid.uidByte[0];
            val2=mfrc522.uid.uidByte[1];
            val3=mfrc522.uid.uidByte[2];
            val4=mfrc522.uid.uidByte[3];
            byte buffer1[18];
            block = 4;
            len = 18;
            //------------------------------------------- GET FIRST NAME
            status = mfrc522.PCD_Authenticate(MFRC522::PICC_CMD_MF_AUTH_KEY_A, 4, &key, &(mfrc522.uid)); //line 834 of MFRC522.cpp file
            if (status != MFRC522::STATUS_OK) {
                  Serial.print(F("Authentication failed: "));
                  Serial.println(mfrc522.GetStatusCodeName(status));
                  return;
                }
                status = mfrc522.MIFARE_Read(block, buffer1, &len);
            if (status != MFRC522::STATUS_OK) {
                  Serial.print(F("Reading failed: "));
                  Serial.println(mfrc522.GetStatusCodeName(status));
                  return;
                }

            //PRINT FIRST NAME
            for (uint8_t i = 0; i < 16; i++){
                  if (buffer1[i] != 32){
                        Serial.write(buffer1[i]);
                      }
                }
            Serial.print(" ");
            //---------------------------------------- GET LAST NAME
            byte buffer2[18];
            block = 1;
            status = mfrc522.PCD_Authenticate(MFRC522::PICC_CMD_MF_AUTH_KEY_A, 1, &key, &(mfrc522.uid)); //line 834
            if (status != MFRC522::STATUS_OK) {
                  Serial.print(F("Authentication failed: "));
                  Serial.println(mfrc522.GetStatusCodeName(status));
                  return;
                }
            status = mfrc522.MIFARE_Read(block, buffer2, &len);
            if (status != MFRC522::STATUS_OK) {
                  Serial.print(F("Reading failed: "));
                  Serial.println(mfrc522.GetStatusCodeName(status));
                  return;
                }

            //PRINT LAST NAME
            for (uint8_t i = 0; i < 16; i++) {
                    Serial.write(buffer2[i] );
                }
            //Serial.println(F("\n**End Reading**\n"));
            delay(1000); //change value if you want to read cards faster
            mfrc522.PICC_HaltA();
            mfrc522.PCD_StopCrypto1();
  //************************RFID FIN*****************************
            myservo.write(0);


           
                  
                  
   // llamo funcion que comunica con el servidor
             httpRequest(val1,val2,val3,val4);
             
             }
//*********************fin loop******************************************


//funcion coneccion al servidor
 int httpRequest(int uno,int dos, int tres, int cuatro){
  
              //comprueba si hay coneccion
          if(client.connect(server,80)){
              // Serial.println("\nConectado");
              //enviar peticion http
              //direccion del archivo php dentro del servidor
              //Serial.println(terminal);
              client.print("GET /FuturoBus/arduino.php?nfc_id=");
              //Mandmos la variablre junto al la linea get
              client.print(uno,HEX);
              client.print(dos,HEX);
              client.print(tres,HEX);
              client.print(cuatro,HEX);
              client.print("&terminal=");
              client.print(terminal);
              client.println(" HTTP/1.0");
              // aqui la ip del servidor php
              client.println("Host: 192.168.1.2");
              client.println("User-Agent: Arduino-Ethernet");
              client.println("Coneccion: Close");
              client.println("Refresh: 5");  
              client.println();
          }else{
              Serial.println("Fallo en la coneccion");
              Serial.println("Desconctando");
              client.stop();
              }
          delay(500);
          //comprobamos si tenemos respuesta con el servidor y almacenamos el nombre del string codigo
          while(client.available()){
          char c = client.read();
          codigo += c;
          //habilitamos la comprobacion del codigo recibido
          fin=true;
          } 
          
          if(fin){
              //Serial.print(codigo);
              //Analizamos la longitud del codigo recibido
              int longitud = codigo.length();
              //buscamos en que posicion del string se encuentra nuestra variable
              int posicion = codigo.indexOf("valor=");
              //borramos lo que haya almacenado en el string nombre
              nombre = "";
              ///Analizamos el codigo obtenido y almacenamos el nombtre del string nombre
              for(int  i = posicion + 6 ; i < longitud; i++){
                      if(codigo[i] == ';') i = longitud;
                      else nombre += codigo[i];
                  }
              //desabilito analisis del codigo
              fin =false;
              //imprime el nombre obtenido
              if(nombre == "1"){

                

                unsigned i,j;
                  Serial.println("Bienvenido: " );
                  digitalWrite(ledVerde,HIGH);
                
                  tone (pitido,700);
                  
                  digitalWrite(pitido,HIGH);
                  delay(800);
                  noTone (pitido);
                  
                  myservo.write(90);
                  delay(2000);
                  digitalWrite(ledVerde,LOW);
                  delay(600);
              }else{
              Serial.println("sin acceso: " );
              digitalWrite(ledRojo,HIGH);
                  tone (pitido,100);
                  
                  digitalWrite(pitido,HIGH);
                  delay(400);
                  noTone (pitido);
                  digitalWrite(pitido,LOW);
                  delay(400);
                  tone (pitido,100);
                  digitalWrite(pitido,HIGH);
                  delay(400);
                  noTone (pitido);
              delay(2000);
              digitalWrite(ledRojo,LOW);
              delay(600);
              }
              //cierro la coneccion
              //Serial.println("Desconectar \n");
              client.stop();
           }
//borra codigo y salir de la funcion de la direccion del servidor

codigo="";
return 1;
}



