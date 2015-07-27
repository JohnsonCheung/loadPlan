Attribute VB_Name = "Rmk__Tst"
Option Explicit

Sub TstAll_Rmk()
NewRmk__Tst
FillWs__Tst
End Sub

Private Sub FillWs__Tst()
Application.ScreenUpdating = False

Dim Ws As Worksheet
Dim Wb As Workbook, App As Application
    Set Ws = WsNew
    Set Wb = Ws.Parent
    
NewRmk(C_Seg1).FillWs Ws, 12 '<====

Debug.Assert Ws.Range("A12").Value = "*柯打送貨指示"
Debug.Assert Ws.Range("A13").Value = "*1"
Debug.Assert Ws.Range("A14").Value = "*2"
Debug.Assert Ws.Range("A15").Value = "*3"

Debug.Assert Ws.Range("B13").Value = Replace("Ord Rmk 1 Line1\nLine2\nLine3", "\n", vbCrLf)
Debug.Assert Ws.Range("B14").Value = Replace("Ord Rmk 2 Line1\nLine2\nLine3", "\n", vbCrLf)
Debug.Assert Ws.Range("B15").Value = Replace("Ord Rmk 3 Line1\nLine2\nLine3", "\n", vbCrLf)

Debug.Assert Ws.Range("A17").Value = "@地址送貨指示"
Debug.Assert Ws.Range("A18").Value = "@1"
Debug.Assert Ws.Range("A19").Value = "@2"
Debug.Assert Ws.Range("A20").Value = "@3"

Debug.Assert Ws.Range("B18").Value = Replace("Adr Rmk 1 Line1\nLine2\nLine3", "\n", vbCrLf)
Debug.Assert Ws.Range("B19").Value = Replace("Adr Rmk 2 Line1\nLine2\nLine3", "\n", vbCrLf)
Debug.Assert Ws.Range("B20").Value = Replace("Adr Rmk 3 Line1\nLine2\nLine3", "\n", vbCrLf)
Stop
Wb.Close False
Application.ScreenUpdating = True
Pass "FillWs__Tst"
End Sub

Private Sub NewRmk__Tst()
Shell LSPth.Que & "CpyQue.bat"
Dim M As Rmk
    Set M = NewRmk(C_Seg1)

Debug.Assert M.NLin = 6 + 3

Dim A$()

A = M.A_RmkOfAdr
    Debug.Assert A(0) = "Adr Rmk 1 Line1\nLine2\nLine3"
    Debug.Assert A(1) = "Adr Rmk 2 Line1\nLine2\nLine3"
    Debug.Assert A(2) = "Adr Rmk 3 Line1\nLine2\nLine3"


A = M.A_RmkOfOrd
    Debug.Assert A(0) = "Ord Rmk 1 Line1\nLine2\nLine3"
    Debug.Assert A(1) = "Ord Rmk 2 Line1\nLine2\nLine3"
    Debug.Assert A(2) = "Ord Rmk 3 Line1\nLine2\nLine3"
Pass "NewRmk__Tst"
End Sub

