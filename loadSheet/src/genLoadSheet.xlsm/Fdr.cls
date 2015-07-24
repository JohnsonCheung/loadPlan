VERSION 1.0 CLASS
BEGIN
  MultiUse = -1  'True
END
Attribute VB_Name = "Fdr"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = False
Attribute VB_Exposed = False
Option Explicit
Public A_Fdr$
Public LoadSheet As New LoadSheet
Public PthInp$
Function Init(Fdr$) As Fdr
A_Fdr = Fdr
PthInp = PthQue & Fdr & "\"
LoadSheet.Init Fdr
Set Init = Me
End Function

Function Gen() As Boolean
Dim O As Boolean
O = LoadSheet.Gen
If O Then
    MovToEr
Else
    PthDlt PthInp, Force:=True
End If
Gen = O
End Function

Function MovToEr$()
Dim A$, B$
A = PthQueErr & TimStmp
MkDir A
A = A & "\" & A_Fdr
B = PthMov(PthInp, A)       '<=== Move the PthInp to PthQueErr
MovToEr = A
If B <> "" Then
    Dim M$()
    Push M, vbTab & "Cannot move folder: "
    Push M, vbTab & "  Fm=[" & PthInp & "]."
    Push M, vbTab & "  To=[" & A & "]."
    Push M, vbTab & "  ErMsg=[" & B & "]"
    WrtLogAy M
End If
End Function
