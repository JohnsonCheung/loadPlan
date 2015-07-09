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
Private A_RmkAy$()
Public NLin%

Property Get RmkAy() As String()
RmkAy = A_RmkAy
End Property

Function Init(Fdr$) As Rmk
A_Fdr = Fdr
Erase A_RmkAy
Dim S As Scripting.TextStream
Dim L$, Ft$
Ft = PthQue & Fdr & "\Rmk.Txt"
If FSO.FileExists(Ft) Then
    Set S = FSO.OpenTextFile(Ft)
    Do While S.AtEndOfStream <> True
        Push A_RmkAy, S.ReadLine
    Loop
    S.Close
End If
NLin = Sz(A_RmkAy)
Set Init = Me
End Function

Function FillRmk(Ws As Worksheet, Rno&) As Boolean
'- Element in RmkAy has 2 fields: RmkKey and Rmk separated by comma
'- Put Sno to col-A
'  Put Fld#1-RmkKey in col-B,
'  Put Fld#2-Rmk    in col-C

'On Error GoTo Er
If Sz(RmkAy) = 0 Then Exit Function
Dim J%, Rge As Range, R&, N%
Ws.Range("A" & Rno).Value = "°e³f«ü¥Ü"
Dim Rmk$, RmkKey$
For J = 0 To UB(RmkAy)
    Dim A$, B$, Ay$()
    Ay = Split(A_RmkAy(J), ",")
    RmkKey = Ay(0)
    Rmk = Ay(1)
    
    R = Rno + J + 1
    
    Set Rge = Ws.Range("A" & R)
    N = StrSubStrCnt(A_RmkAy(J), "\n")
    If N > 1 Then
        Rge.RowHeight = Rge.RowHeight * (N + 1) '<== Set Height
    End If
    
    Ws.Range("A" & R).Value = "#" & J + 1       '<== Put Col-A : #J ==========================================
    Ws.Range("B" & R).Value = RmkKey            '<== Put Col-B : RmkKey$ ======================================
    With Ws.Range("A" & R & ":B" & R)
        .HorizontalAlignment = XlHAlign.xlHAlignCenter
        .VerticalAlignment = XlVAlign.xlVAlignCenter
    End With
    
    '<== Fmt Col-C : LastCol ======================================
    '       LastCol = "M"
    Set Rge = Ws.Range(Ws.Cells(R, "C"), Ws.Cells(R, Ws1LastCol))
    Rge.Merge                                   '<== Merge
    Rge.HorizontalAlignment = XlHAlign.xlHAlignLeft '<== HAlign
    Rge.VerticalAlignment = XlVAlign.xlVAlignCenter '<== VAlign
    Rge.WrapText = True                             '<== Wrap
    Rmk = Replace(Rmk, "\n", vbCrLf)
    Ws.Range("C" & R).Value = Rmk '<== Put Col-C : Rmk$ ================================
Next

'<== Set Border
Set Rge = Ws.Range(Ws.Cells(Rno + 1, "A"), Ws.Cells(Rno + Sz(RmkAy), Ws1LastCol))
RgeSetBdrGrid Rge
Exit Function
Er:
    WrtLog vbTab & "Err in fill remark. ErMsg=[" & Err.Description & "]"
End Function

