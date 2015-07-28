Attribute VB_Name = "VeIde"
Option Explicit
Property Get CurXlsApp() As XlsApp
Static O As XlsApp
If IsNothing(O) Then
    Set O = NewXlsApp(Application)
End If
Set CurXlsApp = O
End Property

Property Get CurVenv() As Venv
Set CurVenv = CurXlsApp.Venv
End Property

Property Get CurPj() As Pj
Dim O As New Pj
Set CurPj = O.NewPj(CurXlsApp.A_Xls.Vbe.ActiveVBProject)
End Property

