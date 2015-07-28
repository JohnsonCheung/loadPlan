VERSION 1.0 CLASS
BEGIN
  MultiUse = -1  'True
END
Attribute VB_Name = "XlsApp"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = False
Attribute VB_Exposed = True
Option Explicit
Public A_Xls As Excel.Application
Public A_Venv As New Venv

Function NewXlsApp(Xls As Application) As XlsApp
Set A_Xls = Xls
Set A_Venv = NewVenv(Xls.Vbe)
Set NewXlsApp = Me
End Function

Property Get Venv() As Venv
Set Venv = A_Venv
End Property
