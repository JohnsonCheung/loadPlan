VERSION 1.0 CLASS
BEGIN
  MultiUse = -1  'True
END
Attribute VB_Name = "App"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = False
Attribute VB_Exposed = True
Option Explicit
Public A_App As Application
Public A_Venv As New Venv

Sub Init(App As Application)
Set A_App = App
A_Venv.Init App.Vbe
End Sub

Property Get Venv() As Venv
Set Venv = A_Venv
End Property
