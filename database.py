import mysql.connector
mydb = mysql.connector.connect(
    host="127.0.0.1",
    user="root",
    passwd="",
    database="printer_materials"

)

add_cannister=("INSERT INTO materials "
               "(Amount,Material,InitialAmount,Printer)"
               "VALUES (%s,%s,%s,%s)")

nextMat=('91','ABS','92','Fortus450')

cursor=mydb.cursor()
cursor.execute(add_cannister,nextMat)
mydb.commit()
cursor.close()
mydb.close()