VERSION 1.0 CLASS
BEGIN
  MultiUse = -1  'True
END
Attribute VB_Name = "Drop"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = False
Attribute VB_Exposed = False
Option Explicit
Private DropLinAy() As DropLin
Property Get NDrop%()
NDrop = Sz(DropLinAy)
End Property
Sub Init(Fdr$)
Dim U%
    Dim Ay$(), P$
    P = Pth.Que & Fdr & "\"
    Ay = PthAyFn(P, "Drop-??.txt")
    U = UB(Ay)

Erase DropLinAy
    If U >= 0 Then Exit Sub

    Dim J%
    For J = 0 To UB(Ay)
        Dim M As DropLin
            Dim Fn$
            Fn = "drop-" & Format(J + 1, "00") & ".txt"
            Set M = New DropLin
            M.Init Fdr, Fn
        PushObj DropLinAy, M
    Next
End Sub

Function FillWs(Ws As Worksheet, ByRef ORno%) As Boolean
Dim J%
ORno = 11
For J = 0 To NDrop - 1
    If DropLinAy(J).FillWs(Ws, ORno) Then FillWs = True
    ORno = ORno + 1
Next
Dim C1, C2, R1, R2
Dim BdrRge As Range
    C1 = 1
    R1 = 11
    C2 = Ws1LastCol
    R2 = 11 + NDrop - 1
    Set BdrRge = Ws.Range(Ws.Cells(R1, C1), Ws.Cells(R2, C2))
RgeSetBdrGrid BdrRge    '<== Border
End Function

