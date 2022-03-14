import sqlite3, hashlib
from tkinter import *
from tkinter import simpledialog
from functools import partial
from tkinter import font
import base64
from cryptography.hazmat.primitives import hashes, kdf
from cryptography.hazmat.primitives.kdf.pbkdf2 import PBKDF2HMAC
from cryptography.hazmat.backends import default_backend
from cryptography.fernet import Fernet
import random

from flask import Flask, request, jsonify
from flask_restful import Api, Resource, reqparse
import requests
import json

import threading
import time

WAIT_TIME = 60

#TO ENCRYPT THE DATABASE

backend = default_backend()
salt = b'2444'
kdf = PBKDF2HMAC(
    algorithm=hashes.SHA256(),
    length=32,
    salt=salt,
    iterations=100000,
    backend=backend
)

encryptionKey = 0


def getUsername(dns):
    cursor.execute("SELECT * FROM uap")
    accounts = cursor.fetchall()
    for account in accounts:
        accountDns = str(decrypt(account[1], encryptionKey).decode('utf-8'))
        if accountDns == str(dns):
            username = str(decrypt(account[2], encryptionKey).decode('utf-8'))
            return username
    return ''


def getPassword(dns, username):
    cursor.execute("SELECT * FROM uap")
    accounts = cursor.fetchall()
    for account in accounts:
        accountDns = str(decrypt(account[1], encryptionKey).decode('utf-8'))
        if accountDns == str(dns):
            user = str(decrypt(account[2], encryptionKey).decode('utf-8'))
            if user == username:
                password = str(decrypt(account[3], encryptionKey).decode('utf-8'))
                return password
    return ''


dns = None
username = None
secret = None
randomApi = None
flag = True

def runApi():
    app = Flask(__name__)
    api = Api(app)

    @app.get('/login')
    def login():
        global username, dns
        print('------------------------------------------------')
        dns = str(request.environ['REMOTE_ADDR']) 
        username = getUsername(dns)
        # print('Username: ' + str(username))
        return jsonify({"username" : username})

    @app.post('/secretInfo')
    def secretInfo():
        global secret, randomApi
        print('------------------------------------------------')
        d = str(request.data.decode('utf-8'))
        data = json.loads(d)
        salt = data['salt']
        iterations = data['iter']
        password = getPassword(dns, username)
        sec = hashlib.pbkdf2_hmac( "sha256", password.encode("utf-8"), salt.encode("utf-8"), iterations, 32)
        secret = sec.hex()
        randomApi = generateRandom()
        # print('Password: ' + str(password))
        # print('Secret: ' + str(secret))
        return jsonify({'random' : randomApi})

    @app.post('/sendByte')
    def sendByte():
        global secret, flag, randomApi
        print('------------------------------------------------')
        d = str(request.data.decode('utf-8'))
        # print('Data(API): ' + d)
        data = json.loads(d)
        index = int(data['index'])
        byteIn = str(data['byte'])
        randServ = data['rand']
        solutionApi = challengeSolution(secret, randomApi)
        if flag:
            if solutionApi[index] == byteIn:
                solutionServ = challengeSolution(secret, randServ)
                byteOut = solutionServ[index]
                # print('SoltutionApi: ' + str(solutionApi))
                # print('SolutionServ: ' + str(solutionServ))
                # print('Index: ' + str(index))
                # print('ByteIn: ' + str(byteIn))
                # print('ByteOut: ' + str(byteOut))
            else:
                print('Failed on iteration:: ' + str(index))
                flag = False
                byteOut = randomByte()
        else:
            byteOut = randomByte()
        randomApi = generateRandom()
        # print('DATA(Serv): ' + str({'byte' : byteOut, 'rand' : randomApi}))
        return jsonify({'byte' : byteOut, 'rand' : randomApi})

    @app.get('/shutdown')
    def shutdown():
        global dns, username, secret, randomApi, flag
        print('------------------------------------------------')
        func = request.environ.get('werkzeug.server.shutdown')
        if func is None:
            raise RuntimeError('Not running with the Werkzeug Server')
        func()
        print('Shutting down...')
        dns = None
        username = None
        secret = None
        randomApi = None
        flag = True
        return 'Shutting down...'

    def stop():
        time.sleep(WAIT_TIME)
        try:
            shutSignal = requests.get('http://0.0.0.0:5000/shutdown')
            print('Stoped listening')
        except Exception:
            print('Already shut')

    def run():
        if __name__ == '__main__':
            app.run(host='0.0.0.0', port=5000)

    apiThread = threading.Thread(target=run)
    stopThread = threading.Thread(target=stop)
    apiThread.start()
    stopThread.start()


def generateRandom():
    n = 32
    r = ''
    char = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'
    for i in range(0,n):
        index = random.randrange(0, len(char)-1)
        r += char[index]
    return r

def randomByte():
    bytes = '0123456789abcdef'
    index = random.randrange(0, len(bytes)-1)
    return bytes[index]

# receives the randomValue from the WebSite and calculates an hash
def challengeSolution(secret, random):
    challenge = secret + random
    hash = hashlib.sha256(challenge.encode()).hexdigest()
    return hash


def encrypt(message: bytes, key: bytes) -> bytes:
    return Fernet(key).encrypt(message)

def decrypt(message: bytes, token: bytes) -> bytes:
    return Fernet(token).decrypt(message)

#DATABASE

with sqlite3.connect("uap.db", check_same_thread=False) as db:
    cursor = db.cursor()

cursor.execute(""" 
CREATE TABLE IF NOT EXISTS masterpass(
id INTEGER PRIMARY KEY, 
password TEXT NOT NULL 
);
""")

cursor.execute(""" 
CREATE TABLE IF NOT EXISTS uap(
id INTEGER PRIMARY KEY, 
dns TEXT NOT NULL, 
username TEXT NOT NULL,
password TEXT NOT NULL
);
""")

def popUp(text):
    answer = simpledialog.askstring("input string", text)
    
    return answer

#INTERFACE 

window = Tk()

window.title("User Authentication Application")

def hashPassword(input):
    hash = hashlib.md5(input)
    hash = hash.hexdigest()

    return hash

def firstScreen():
    window.geometry("250x150")

    lbl = Label(window, text="Create Master Password")
    lbl.pack()

    pass1 = Entry(window, width=20, show="*")
    pass1.pack()
    pass1.focus()

    lbl1 = Label(window, text="Re-enter Master Password")
    lbl1.pack()

    pass2 = Entry(window, width=20, show="*")
    pass2.pack()
    pass2.focus()

    lblwarn = Label(window)
    lblwarn.pack()

    def savePassword():
        if pass1.get() == pass2.get():

            hashpass = hashPassword(pass1.get().encode('utf-8'))

            global encryptionKey
            encryptionKey = base64.urlsafe_b64encode(kdf.derive(pass1.get().encode()))

            insert_password = """ INSERT INTO masterpass(password) VALUES(?) """
            cursor.execute(insert_password, [(hashpass)])
            db.commit()

            appScreen()

        else:
            lblwarn.config(text="Passwords don't match")

    btn = Button(window, text="Save", command=savePassword)
    btn.pack()

def loginScreen(): 
    window.geometry("250x100")

    lbl = Label(window, text="Enter Master Password")
    lbl.config(anchor=CENTER)
    lbl.pack()

    txt = Entry(window, width=20, show="*")
    txt.pack()
    txt.focus()

    lblwarn = Label(window)
    lblwarn.pack()

    def getMasterPassword():
        checkhashpass = hashPassword(txt.get().encode('utf-8'))

        global encryptionKey
        encryptionKey = base64.urlsafe_b64encode(kdf.derive(txt.get().encode()))

        cursor.execute("SELECT * FROM masterpass WHERE id=1 AND password=?", [(checkhashpass)])

        return cursor.fetchall()

    def checkPassword():
        match = getMasterPassword()

        if match:
            appScreen()
        else:
            txt.delete(0, 'end')
            lblwarn.config(text="Wrong Password")
            

    btn = Button(window, text="Submit", command=checkPassword)
    btn.pack()

def appScreen():
    for widget in window.winfo_children():
        widget.destroy() 

    def addEntry():
        text1 = "DNS"
        text2 = "Username"
        text3 = "Password"

        dns = encrypt(popUp(text1).encode(), encryptionKey)
        username = encrypt(popUp(text2).encode(), encryptionKey)
        password = encrypt(popUp(text3).encode(), encryptionKey)

        insert_fields = """ INSERT INTO uap(dns, username, password) 
        VALUES (?, ?, ?)"""

        cursor.execute(insert_fields, (dns, username, password))
        db.commit()

        appScreen()
    
    def removeEntry(input):
        cursor.execute("DELETE FROM uap WHERE id = ?", (input,))
        db.commit()

        appScreen()

    def getUsername(dns):
        print('------------------------------------------------')
        cursor.execute("SELECT * FROM uap")
        accounts = cursor.fetchall()
        for account in accounts:
            accountDns = str(decrypt(account[1], encryptionKey).decode('utf-8'))
            if accountDns == dns:
                username = str(decrypt(account[2], encryptionKey).decode('utf-8'))
                print('Username: ' + username)
            else: return ''
        print('------------------------------------------------')
        return username

    window.geometry("900x350")
    
    btn = Button(window, text="Add Entry", command=addEntry)
    btn.grid(row=1, column=0)

    btnc = Button(window, text="Start Authentication", command=runApi)
    btnc.grid(row=1, column=2)

    lbl = Label(window, text="DNS", font=font.BOLD)
    lbl.grid(row=2, column=0, padx=80)
    lbl = Label(window, text="USERNAME", font=font.BOLD)
    lbl.grid(row=2, column=1, padx=80)
    lbl = Label(window, text="PASSWORD", font=font.BOLD)
    lbl.grid(row=2, column=2, padx=80)

    cursor.execute("SELECT * FROM uap")
    if cursor.fetchall() != None:
        i = 0
        while True:
            cursor.execute("SELECT * FROM uap")
            array = cursor.fetchall()

            if (len(array) == 0):
                break

            lbl1 = Label(window, text=(decrypt(array[i][1], encryptionKey)))
            lbl1.grid(column=0, row=i+3)
            lbl1 = Label(window, text=(decrypt(array[i][2], encryptionKey)))
            lbl1.grid(column=1, row=i+3)
            lbl1 = Label(window, text=(decrypt(array[i][3], encryptionKey)))
            lbl1.grid(column=2, row=i+3)

            btn = Button(window, text="Delete", command=partial(removeEntry, array[i][0]))
            btn.grid(column=3, row=i+3, pady=10)

            i+=1

            cursor.execute("SELECT * FROM uap")
            if len(cursor.fetchall()) <= i:
                break


cursor.execute("SELECT * FROM masterpass")

if cursor.fetchall():
    loginScreen()
else: 
    firstScreen()

window.mainloop()