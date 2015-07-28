Attribute VB_Name = "new_XlsApp"
Option Explicit

Function NewXlsApp(Xls As Excel.Application) As XlsApp
Dim O As New XlsApp
Set NewXlsApp = O.NewXlsApp(Xls)
End Function

