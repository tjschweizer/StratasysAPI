#import database
import utils
import network
import parsePrinterJson
import pickle
#jsonData=network.getActivePrinters()
jsonData=pickle.load(open("JsonData.p","rb"))
printerData=pickle.load(open("printerData.p","rb"))
#printerData = parsePrinterJson.parseJson(jsonData)
#pickle.dump(jsonData,open("JsonData.p","wb"))
#pickle.dump(printerData,open("printerData.p","wb"))
print(jsonData)




