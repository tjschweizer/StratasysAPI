# Many Functions From https://github.com/nkaminski/stratasys_api/

from struct import *
import socket
from multiping import MultiPing
import config as cf

IP_Port="53742"

def make_request(sock, req):
    req_struct = pack('64s', req)
    sock.sendall(req_struct)

def recv_data(sock, size):
    data = b''
    needed = size
    while (needed > 0):
        data += sock.recv(needed)
        needed = size - len(data)
    return data[:data.find(b'\x00')]

def printer_get_data(h, p=53742):
    s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
    s.settimeout(4.0)
    try:
        s.connect((h, p))
        make_request(s, b'GetFile')
        make_request(s, b'status.sts')
        make_request(s, b'NA')
        recv_data(s, 64)
        recv_data(s, 64)
        make_request(s, b'OK')
        respsz = int(recv_data(s, 64).decode('ascii'))
        make_request(s, b'OK')
        outdat = recv_data(s, respsz)
    except(socket.timeout):
        outdat = None
    s.close()
    return outdat


def getActivePrinters():
    mp = MultiPing([cf.IP_Fortus450,cf.IP_Fortus250_New,cf.IP_Fortus250_Old,cf.IP_uPrint])
    mp.send()
    responses, no_responses=mp.receive(1)
    tmp=[]
    for printer in responses:
        printerData=printer_get_data(printer).decode("utf-8")
        tmp.append(printerData)
        print(tmp)
    return tmp