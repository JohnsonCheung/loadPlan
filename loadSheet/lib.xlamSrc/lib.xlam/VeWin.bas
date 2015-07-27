Attribute VB_Name = "VeWin"
Option Explicit

Sub WinClsAll()
Dim I As VBIDE.Window
For Each I In Application.Vbe.Windows
    I.Close
Next
End Sub
