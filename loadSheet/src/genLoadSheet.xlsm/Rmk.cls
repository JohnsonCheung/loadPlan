VERSION 1.0 CLASS
BEGIN
  MultiUse = -1  'True
END
Attribute VB_Name = "Rmk"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = False
Attribute VB_Exposed = True
Option Explicit
Public A_RmkOfOrd As New RmkOfOrd
Public A_RmkOfAdr As New RmkOfAdr
Public NLin%

Function NewRmk(Seg$) As Rmk
A_RmkOfOrd.NewRmkOfOrd Seg
A_RmkOfAdr.NewRmkOfAdr Seg
NLin = 3 + A_RmkOfOrd.NLin + A_RmkOfAdr.NLin
Set NewRmk = Me
End Function

Sub FillWs(Ws As Worksheet, ByRef ORno&)
'- Element in RmkAy has 2 fields: RmkKey and Rmk separated by comma
A_RmkOfOrd.FillWs Ws, ORno
A_RmkOfAdr.FillWs Ws, ORno
End Sub
