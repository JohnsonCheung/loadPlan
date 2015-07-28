Attribute VB_Name = "new_Venv"
Option Explicit

Function NewVenv(Vbe As Vbe) As Venv
Dim O As New Venv
Set NewVenv = O.NewVenv(Vbe)
End Function

