import json
def parseJson(jsonInData):
    if len(jsonInData)>0:
        printerReturn={}
        for printer in jsonInData:
            #start by getting modeler type
            mTypeLoc= printer.find("modelerType", 0, len(printer))
            mTypeEndLoc = printer.find("\n\t", mTypeLoc, len(printer))
            mType = printer[mTypeLoc + len("modelerType") + 1:mTypeEndLoc]

            firstSection = "set machineStatus(general)"
            secondSection = "set machineStatus(cassette)"
            fourthSection = "set machineStatus(currentJob)"
            fifthSection = "set machineStatus(previousJob)"
            sixthSection = "set machineStatus(queue)"
            is450=0
            if mType=="waikiki":
                #Fortus 450
                thirdSection = "set machineStatus(waikiki)"
                is450 = 1
            elif mType=="kapaa":
                #Fortus 250

                thirdSection = "set machineStatus(mariner)"
            elif mType=="paia":
                thirdSection = "set machineStatus(mariner)"

            status=[]
            status.append(printer[printer.find(firstSection):printer.find(secondSection)-2].split('\n'))
            status.append(printer[printer.find(secondSection):printer.find(thirdSection) - 2].split('\n'))
            status.append(printer[printer.find(thirdSection):printer.find(fourthSection) - 2].split('\n'))
            status.append(printer[printer.find(fourthSection):printer.find(fifthSection) - 2].split('\n'))
            status.append(printer[printer.find(fifthSection):printer.find(sixthSection) - 2].split('\n'))
            status.append(printer[printer.find(sixthSection):len(printer)].split('\n'))

            printerDict={}
            catIter=0
            for category in status:
                tmpDict = {}

                if catIter == 0:
                    for lineToSplit in category[1:len(category)-1]:
                        tmpDict.update(lineToDict(lineToSplit))
                    printerDict['General Status']=tmpDict
                elif catIter == 1:
                    spool = []
                    tmpCat = category[2:len(category) - 1]
                    tmpCassetteDict={}
                    while 1:
                        if len(tmpCat) < 5:
                            break
                        else:
                            spool.append(tmpCat[0:tmpCat.index("}")])
                            tmpCat = tmpCat[tmpCat.index("}") + 2:len(tmpCat)]
                    spoolIter = 0
                    for singleSpool in spool:
                        spoolDict={}
                        for lineToSplit in singleSpool:
                            spoolDict.update(lineToDict(lineToSplit))
                        key = "Spool " + str(spoolIter)
                        tmpCassetteDict[key] = spoolDict
                        spoolIter=spoolIter+1
                    printerDict['Cassette Status']=tmpCassetteDict
                elif catIter == 2:
                    for lineToSplit in category[1:len(category) - 1]:
                        tmpDict.update(lineToDict(lineToSplit))
                    printerDict['Modeler Status'] = tmpDict
                elif catIter == 3:
                    for lineToSplit in category[1:len(category) - 1]:
                        tmpDict.update(lineToDict(lineToSplit))
                    printerDict['Current Job Status'] = tmpDict
                elif catIter == 4:
                    for lineToSplit in category[1:len(category) - 1]:
                        tmpDict.update(lineToDict(lineToSplit))
                    printerDict['Previous Job Status'] = tmpDict
                elif catIter == 5:
                    for lineToSplit in category[1:len(category) - 1]:
                        tmpDict.update(lineToDict(lineToSplit))
                    printerDict['Queue Status'] = tmpDict

                catIter=catIter+1
            if printerDict['Modeler Status']['productSerialNumber'] == "P17861":
                printerReturn['New Fortus 250mc']=printerDict
            elif printerDict['Modeler Status']['productSerialNumber'] == "P13448":
                printerReturn['Old Fortus 250mc']=printerDict
            elif printerDict['Modeler Status']['productSerialNumber'] == "P51747":
                printerReturn['uPrint'] = printerDict
            elif printerDict['Modeler Status']['productSerialNumber'] == "K10447":
                printerReturn['Fortus 450mc']=printerDict

        return printerReturn




def lineToDict(line):
    key=line[line.find("-")+1:line.find(" ",line.find("-"))]
    value=line[line.find(" ",line.find("-"))+1:len(line)]
    if value[0]=="{" :
       value=value[1:len(value)-1]
    return {key:value}