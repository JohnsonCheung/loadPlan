Attribute VB_Name = "GlobalObj"
Option Explicit
Property Get CurApp() As App
Static O As App
If IsNothing(O) Then
    Set O = New App
    O.Init Application
End If
Set CurApp = O
End Property
Property Get CurVenv() As Venv
Set CurVenv = CurApp.Venv
End Property
Property Get CurPj() As Pj
Dim O As New Pj
O.Init CurApp.A_App.Vbe.ActiveVBProject
Set CurPj = O
End Property

