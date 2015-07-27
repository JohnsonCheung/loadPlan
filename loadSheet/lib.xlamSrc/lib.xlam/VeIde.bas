Attribute VB_Name = "VeIde"
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
Set CurPj = O.NewPj(CurApp.A_App.Vbe.ActiveVBProject)
End Property

