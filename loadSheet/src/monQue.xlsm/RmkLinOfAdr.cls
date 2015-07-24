VERSION 1.0 CLASS
BEGIN
  MultiUse = -1  'True
END
Attribute VB_Name = "RmkLinOfAdr"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = False
Attribute VB_Exposed = False
Option Explicit
Public CusCd$
Public AdrCd$
Public Rmk$
Sub ReadLin(Lin$)
Dim Ay$()
Ay = Split(Lin, ",")
CusCd = Ay(0)
AdrCd = Ay(1)
Rmk = Ay(2)
End Sub

Sub FillLin(Ws As Worksheet, Rno&, Idx%)

    Dim Rge1 As Range
        Set Rge1 = Ws.Range("A" & Rno)
        Rge1.Value = "*" & (Idx + 1)             '<== Put Col-A : @J or *J where @/* comes from first char of Des ==
        Rge1.HorizontalAlignment = xlHAlignCenter
        Rge1.VerticalAlignment = xlVAlignCenter
    
    Dim Rge2 As Range
    Dim Rmk$, NLin%
        Set Rge2 = Ws.Range("B" & Rno)
        Rmk = Replace(Me.Rmk, "\n", vbCrLf)
        NLin = StrSubStrCnt(Me.Rmk, "\n")
        If NLin > 1 Then Rge2.RowHeight = Rge2.RowHeight * (NLin + 1)    '<== Set Height
        Rge2.Value = Rmk                                                 '<== Set Rmk
   
    Dim Rge3 As Range
        Set Rge3 = Ws.Range(Ws.Cells(Rno, "B"), Ws.Cells(Rno, Ws1LastCol))
        Rge3.Merge                                   '<== Merge
    
    Dim Rge4 As Range
        Set Rge4 = Ws.Range("A" & Rno & ":B" & Rno)
        With Rge4
            .WrapText = True
            .VerticalAlignment = XlVAlign.xlVAlignCenter
        End With
End Sub

