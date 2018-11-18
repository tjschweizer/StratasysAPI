import config as cf
import stratasys

IP_Port="53742"
printerData=stratasys.stratasys_out_proc(stratasys.printer_get_data(cf.IP_Fortus450))
tmp.append(printerData)
print(printerData)

#print(responses)
#print(no_responses)



