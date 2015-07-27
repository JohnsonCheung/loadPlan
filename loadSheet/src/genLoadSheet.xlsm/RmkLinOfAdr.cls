VERSION 1.0 CLASS
BEGIN
  MultiUse = -1  'True
END
Attribute VB_Name = "RmkLinOfAdr"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = False
Attribute VB_Exposed = True
Option Explicit
Public Cus$
Public Adr$
Public Rmk$

Function NewRmkLinOfAdr(Lvc$) As RmkLinOfAdr
Dim Ay$()
Ay = Split(Lvc$, ",")
Cus = Trim(Ay(0))
Adr = Trim(Ay(1))
Rmk = Trim(Ay(2))
Set NewRmkLinOfAdr = Me
End Function

Sub FillLin(Ws As Worksheet, Rno&, Idx%)
Dim R1 As Range
Dim R2 As Range
Dim R3 As Range
Dim R4 As Range
Dim R4A As Range
    Set R1 = WsRC(Ws, Rno, "A")
    Set R2 = WsRC(Ws, Rno, "B")
    Set R3 = WsRC(Ws, Rno, "C")
    Set R4 = WsRC(Ws, Rno, "D")
    Set R4A = WsRCC(Ws, Rno, "A", Ws1LastCol)
    
If Me.NLin > 1 Then R1.RowHeight = 15 * Me.NLin     '<== Set Height
    
'----
    R1.Value = "@" & (Idx + 1)
    R2.Value = Me.Cus
    R3.Value = Me.Adr
    R4.Value = UnEscLf(Me.Rmk)
    

Ws.Range(Ws.Cells(Rno, "D"), Ws.Cells(Rno, Ws1LastCol)).Merge '<== Merge

'----
    R1.HorizontalAlignment = xlHAlignCenter
    R2.HorizontalAlignment = xlHAlignCenter
    R3.HorizontalAlignment = xlHAlignCenter
    R4.HorizontalAlignment = xlHAlignLeft

    R1.VerticalAlignment = xlVAlignCenter
    R2.VerticalAlignment = xlVAlignCenter
    R3.VerticalAlignment = xlVAlignCenter
    R4.VerticalAlignment = xlVAlignCenter

    R4.WrapText = True
    
'----
    R1.BorderAround XlLineStyle.xlContinuous, xlThin
    R2.BorderAround XlLineStyle.xlContinuous, xlThin
    R3.BorderAround XlLineStyle.xlContinuous, xlThin
    R4A.BorderAround XlLineStyle.xlContinuous, xlThin
End Sub

Property Get NLin%()
NLin = StrSubStrCnt(Me.Rmk, "\n") + 1
End Property