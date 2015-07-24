VERSION 1.0 CLASS
BEGIN
  MultiUse = -1  'True
END
Attribute VB_Name = "RmkOfAdr"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = False
Attribute VB_Exposed = False
Option Explicit
Private A_RmkLinAy() As RmkLinOfAdr
Sub Init(Fdr$)
Dim Ay$()
    Dim P$, F$
    P = PthQue & Fdr & "\"
    F = P & "rmk_OfAdr.Txt"
    If Fso.FileExists(F) Then Ay = FtAy(F)
Dim J%
For J = 0 To UB(Ay)
    Dim M As New RmkLinOfAdr
    M.ReadLin Ay(J)
    PushObj A_RmkLinAy, M
Next
End Sub
Property Get NLin%()
NLin = Sz(A_RmkLinAy)
End Property

Function FillWs(Ws As Worksheet, ByRef ORno&) As Boolean
Const C = "*柯打送貨指示"
If NLin = 0 Then
    Ws.Range("A" & ORno).Value = "沒有 " & C
    ORno = ORno + 2
    Exit Function
End If


Dim Rge0 As Range
    Set Rge0 = Ws.Range("A" & ORno)
    Rge0.Value = C

Dim J%
For J = 0 To NLin - 1
    A_RmkLinAy(J).FillLin Ws, ORno, J
    ORno = ORno + 1
Next


Dim Rge5 As Range
    Set Rge5 = Ws.Range(Ws.Cells(ORno + 1, "A"), Ws.Cells(ORno + NLin, Ws1LastCol))
    RgeSetBdrGrid Rge5 '<== Set Border

ORno = ORno + 2
Exit Function
Er:
    WrtLog vbTab & "Err in fill remark. ErMsg=[" & Err.Description & "]"
    FillWs = True
End Function
