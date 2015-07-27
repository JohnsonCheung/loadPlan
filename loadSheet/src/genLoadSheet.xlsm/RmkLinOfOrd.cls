VERSION 1.0 CLASS
BEGIN
  MultiUse = -1  'True
END
Attribute VB_Name = "RmkLinOfOrd"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = False
Attribute VB_Exposed = True
Option Explicit
Public Cus$
Public Rmk$

Function NewRmkLinOfOrd(Lvc$) As RmkLinOfOrd
Dim Ay$()
Ay = Split(Lvc$, ",")
Cus = Trim(Ay(0))
Rmk = Trim(Ay(1))
Set NewRmkLinOfOrd = Me
End Function

Sub FillLin(Ws As Worksheet, Rno&, Idx%)
Dim R1 As Range
Dim R2 As Range
Dim R3 As Range
Dim R4 As Range
    Set R1 = Ws.Cells(Rno, "A")
    Set R2 = Ws.Cells(Rno, "B")
    Set R3 = Ws.Cells(Rno, "D")


If Me.NLin > 1 Then R1.RowHeight = R1.RowHeight * (Me.NLin + 1)    '<== Set Height
    
'----
    R1.Value = "*" & (Idx + 1)
    R2.Value = Me.Cus
    R3.Value = UnEscLf(Me.Rmk)
    

Ws.Range(Ws.Cells(Rno, "B"), Ws.Cells(Rno, "C")).Merge '<== Merge

Ws.Range(Ws.Cells(Rno, "B"), Ws.Cells(Rno, Ws1LastCol)).Merge '<== Merge

'----
    R1.HorizontalAlignment = xlHAlignCenter
    R1.VerticalAlignment = xlVAlignCenter

Dim Rge As Range
    Set Rge = Ws.Range("A" & Rno & ":B" & Rno)
    With Rge
        .WrapText = True
        .VerticalAlignment = XlVAlign.xlVAlignCenter
    End With
End Sub

Property Get NLin%()
NLin = StrSubStrCnt(Me.Rmk, "\n") + 1
End Property

