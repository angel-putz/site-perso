#include <ModbusSerial.h>
#include <Hmi4DinBox.h>
#include "PinChangeInterrupt.h"

// Used Pins
const int Relais1ON = 5;
const int Relais1OFF = 11;

const int Relais2ON = 13;
const int Relais2OFF = 15;

const int Relais3ON = 4;
const int Relais3OFF = 17;
 
const int TxenPin = 22; 
const int SectPin = 16;
const byte MBLED = 10;

const byte SlaveId = 10;

const int Relais1Coil = 0;
const int Relais2Coil = 1;
const int Relais3Coil = 2;
const int SectIsts = 0;

volatile byte stateMBLED;
volatile byte stateSect;

const int ST1 = 14;
const int ST2 = 8;
const int ST3 = 9;

int choix = 1;

const long interval = 500;
unsigned long currentMillis;
unsigned long LedSectBlink = 0;
int LedSectState = LOW;

#define MySerial Serial1 
#define Console Serial

Hmi4DinBox hmi;

void LED() {
  setState();
}

const unsigned long Baudrate = 19200;

ModbusSerial mb (MySerial, SlaveId, TxenPin);

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

void setup() {
  Console.begin (Baudrate);
  if (!hmi.begin (24, false)) {

    Console.println("hmi.begin() failed !");
    exit (1);
  }
  
  pinMode (Relais1ON, OUTPUT);
  pinMode (Relais1OFF, OUTPUT);
  pinMode (Relais2ON, OUTPUT);
  pinMode (Relais2OFF, OUTPUT);
  pinMode (Relais3ON, OUTPUT);
  pinMode (Relais3OFF, OUTPUT);

  pinMode(ST1, INPUT);
  pinMode(ST1, INPUT_PULLUP);
  pinMode(ST2, INPUT);
  pinMode(ST2, INPUT_PULLUP);
  pinMode(ST3, INPUT);
  pinMode(ST3, INPUT_PULLUP);

  pinMode(MBLED, INPUT);

  Console.begin (Baudrate);
  if (!hmi.begin (24, false)) {
    Console.println("hmi.begin() failed !");
    exit(1);

    stateMBLED = 0;
  }

  MySerial.begin (Baudrate, MB_PARITY_EVEN);

  mb.config (Baudrate);
  mb.setAdditionalServerData ("LAMP"); 

  mb.addCoil (Relais1Coil);
  mb.addCoil (Relais2Coil);
  mb.addCoil (Relais3Coil);
  mb.addIsts(SectIsts);
  delay(500);

  attachPCINT(digitalPinToPCINT(MBLED), LED, CHANGE);

  hmi.lcd.display();
  hmi.lcd.clear();
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

void setState() {
  // Switch Led state
  if (digitalRead(MBLED)) {
    stateMBLED = 1;
  } else {
    stateMBLED = 2;
  }
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

void setLed() {
  if (stateMBLED == 1) {
    hmi.led.set(LED4);
    stateMBLED = 0;
  } else if (stateMBLED == 2) {
    hmi.led.clear(LED4);
    stateMBLED = 0;
  }
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

void Secteur() {
  stateSect = digitalRead(SectPin);
  if (stateSect == HIGH) {
    if (currentMillis - LedSectBlink >= interval) {
      LedSectBlink = currentMillis;
      if (LedSectState == LOW) {
        LedSectState = HIGH;
        hmi.led.set(LED5);
        hmi.lcd.setCursor(1, 0);
        hmi.lcd.print("Secteur Perdu");
      } else {
        LedSectState = LOW;
        hmi.led.clear(LED5);
        hmi.lcd.setCursor(1, 0);
        hmi.lcd.print("Secteur Perdu");
      }
    }
  } else {
    hmi.led.clear(LED1);
    hmi.lcd.setCursor(1, 0);
    hmi.lcd.print("Secteur OK   ");
  }
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

void IHM() {
  if (hmi.keyb.available()) { 
    if (hmi.keyb.pressed()) {
      byte key = hmi.keyb.key();
      if ( (key == KLEFT) || (key == KRIGHT) || (key == KUP) || (key == KDOWN)) {
        if (key == KDOWN) {
          choix = choix - 1;}
        if (key == KUP) {
          choix = choix + 1;}
        if (choix == 1) {
          hmi.lcd.setCursor (0, 0);
          hmi.lcd.print("relais 1    ");
          if (key == KLEFT) {
            hmi.lcd.setCursor (0, 0);
            hmi.lcd.print("relais 1 on ");
            digitalWrite(Relais1OFF, LOW);
            digitalWrite(Relais1ON , HIGH);}
          else if (key == KRIGHT) {
            hmi.lcd.setCursor (0, 0);
            hmi.lcd.print("relais 1 off");
            digitalWrite(Relais1ON, LOW);
            digitalWrite(Relais1OFF , HIGH);}}
        if (choix == 2) {
          hmi.lcd.setCursor (0, 0);
          hmi.lcd.print("relais 2    ");
          if (key == KLEFT) {
            hmi.lcd.setCursor (0, 0);
            hmi.lcd.print("relais 2 on ");
            digitalWrite(Relais2OFF , LOW);
            digitalWrite(Relais2ON , HIGH);}
          else if (key == KRIGHT) {
            hmi.lcd.setCursor (0, 0);
            hmi.lcd.print("relais 2 off");
            digitalWrite(Relais2ON, LOW);
            digitalWrite(Relais2OFF , HIGH);}}
        if (choix == 3) {
          hmi.lcd.setCursor (0, 0);
          hmi.lcd.print("relais 3    ");
          if (key == KLEFT) {
            hmi.lcd.setCursor (0, 0);
            hmi.lcd.print("relais 3 on ");
            digitalWrite(Relais3OFF, LOW);
            digitalWrite(Relais3ON , HIGH);}
          else if (key == KRIGHT) {
            hmi.lcd.setCursor (0, 0);
            hmi.lcd.print("relais 3 off");
            digitalWrite(Relais3ON, LOW);
            digitalWrite(Relais3OFF , HIGH);}}
        if (choix == 4) {
          choix = 1;
          hmi.lcd.setCursor (0, 0);
          hmi.lcd.print("relais 1    ");}
        if (choix == 0) {
          choix = 3;
          hmi.lcd.setCursor (0, 0);
          hmi.lcd.print("relais 3    ");}}}}}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

void loop() {
  currentMillis = millis();

  mb.task();
  
  !mb.setIsts(SectIsts, digitalRead(SectPin));
  digitalWrite (Relais1ON, mb.Coil(Relais1Coil));
  digitalWrite (Relais1OFF, !mb.Coil(Relais1Coil));
  digitalWrite (Relais2ON, mb.Coil(Relais2Coil));
  digitalWrite (Relais2OFF, !mb.Coil(Relais2Coil));
  digitalWrite (Relais3ON, mb.Coil(Relais3Coil));
  digitalWrite (Relais3OFF, !mb.Coil(Relais3Coil));

  Secteur();
  setLed();
  IHM();
}
