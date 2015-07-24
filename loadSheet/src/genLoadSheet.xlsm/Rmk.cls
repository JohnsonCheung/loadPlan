VERSION 1.0 CLASS
BEGIN
  MultiUse = -1  'True
END
Attribute VB_Name = "Rmk"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = False
Attribute VB_Exposed = False
Option Explicit
Private A_Fdr$
Public A_RmkOfOrd As New RmkOfOrd
Public A_RmkOfAdr As New RmkOfAdr
Public NLin%

Sub Init(Fdr$)
A_Fdr = Fdr
A_RmkOfOrd.Init Fdr
A_RmkOfAdr.Init Fdr
NLin = 3 + A_RmkOfOrd.NLin + A_RmkOfAdr.NLin
End Sub

Function FillWs(Ws As Worksheet, ByRef ORno&) As Boolean
'- Element in RmkAy has 2 fields: RmkKey and Rmk separated by comma
If A_RmkOfOrd.FillWs(Ws, ORno) Then FillWs = True
If A_RmkOfAdr.FillWs(Ws, ORno) Then FillWs = True
End Function
