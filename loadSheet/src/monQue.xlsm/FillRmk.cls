VERSION 1.0 CLASS
BEGIN
  MultiUse = -1  'True
END
Attribute VB_Name = "FillRmk"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = False
Attribute VB_Exposed = False
Option Explicit
Private A_Fdr$
Private A_RmkAy_OfOrd$()
Private A_RmkAy_OfAdr$()
Public NLin%
Property Get RmkAy_OfOrd() As String()
RmkAy_OfOrd = A_RmkAy_OfOrd
End Property
Property Get RmkAy_OfAdr() As String()
RmkAy_OfAdr = A_RmkAy_OfAdr
End Property

Sub Init(Fdr$)
A_Fdr = Fdr
Erase A_RmkAy_OfOrd
Erase A_RmkAy_OfAdr
Dim Pth$
Pth = PthQue & Fdr & "\"
A_RmkAy_OfOrd$ = FtAy(Pth & "rmk_OfOrd.Txt")
A_RmkAy_OfAdr$ = FtAy(Pth & "rmk_OfAdr.Txt")
NLin = 3 + Sz(A_RmkAy_OfOrd) + Sz(A_RmkAy_OfAdr)
End Sub

Function FillRmk(Ws As Worksheet, ByRef ORno&) As Boolean
'- Element in RmkAy has 2 fields: RmkKey and Rmk separated by comma
If FillRmk__OnePart(Ws, ORno, A_RmkAy_OfOrd, "*柯打送貨指示") Then FillRmk = True
If FillRmk__OnePart(Ws, ORno, A_RmkAy_OfAdr, "@地址送貨指示") Then FillRmk = True
End Function
Private Function FillRmk__OnePart(Ws As Worksheet, ORno&, RmkAy$(), Des$) As Boolean
If Sz(RmkAy) = 0 Then
    Ws.Range("A" & ORno).Value = "沒有 " & Des
    ORno = ORno + 2
    Exit Function
End If


Dim Rge0 As Range
    Set Rge0 = Ws.Range("A" & ORno)
    Rge0.Value = Des

Dim J%
For J = 0 To UB(RmkAy)
    Dim R&
        R = ORno + J + 1
    
    
    Dim Rge1 As Range
        Set Rge1 = Ws.Range("A" & R)
        Rge1.Value = Left(Des, 1) & (J + 1)             '<== Put Col-A : @J or *J where @/* comes from first char of Des ==
        Rge1.HorizontalAlignment = xlHAlignCenter
        Rge1.VerticalAlignment = xlVAlignCenter
    
    Dim Rge2 As Range
    Dim NLin%
    Dim Rmk$
        Set Rge2 = Ws.Range("B" & R)
        Rmk = Replace(RmkAy(J), "\n", vbCrLf)
        NLin = StrSubStrCnt(RmkAy(J), "\n")
        If NLin > 1 Then Rge2.RowHeight = Rge2.RowHeight * (NLin + 1)    '<== Set Height
        Rge2.Value = Rmk                                             '<== Set Rmk
   
    Dim Rge3 As Range
        Set Rge3 = Ws.Range(Ws.Cells(R, "B"), Ws.Cells(R, Ws1LastCol))
        Rge3.Merge                                   '<== Merge
    
    Dim Rge4 As Range
        Set Rge4 = Ws.Range("A" & R & ":B" & R)
        With Rge4
            .WrapText = True
            .VerticalAlignment = XlVAlign.xlVAlignCenter
        End With
Next


Dim Rge5 As Range
    Set Rge5 = Ws.Range(Ws.Cells(ORno + 1, "A"), Ws.Cells(ORno + Sz(RmkAy), Ws1LastCol))
    RgeSetBdrGrid Rge5 '<== Set Border

ORno = ORno + Sz(RmkAy) + 2
Exit Function
Er:
    WrtLog vbTab & "Err in fill remark. ErMsg=[" & Err.Description & "]"
    FillRmk__OnePart = True
End Function

