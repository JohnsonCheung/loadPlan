VERSION 1.0 CLASS
BEGIN
  MultiUse = -1  'True
END
Attribute VB_Name = "LSApp"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = False
Attribute VB_Exposed = True
Option Explicit
Sub Gen()
Dim I, Fdr As Fdr
Dim A$()
A = QueSegAy
If Sz(A) > 0 Then
    For Each I In QueSegAy
        Fdr(CStr(I)).Gen
    Next
End If
'ªþ­¶
End Sub

Function Fdr(Seg_or_Idx) As Fdr
Dim Seg$
    Dim P
    P = Seg_or_Idx
    If IsStr(P) Then
        Seg = P
    ElseIf IsInt(P) Then
        Seg = QueSeg(CInt(P))
    Else
        Err.Raise 1
    End If
Set Fdr = NewFdr(Seg)
End Function

Function QueSeg(Idx%) As Fdr
Dim A$()
A = QueSegAy
QueSeg = A(Idx)
End Function

Function QueSegAy() As String()
QueSegAy = PthAyFdr(LSPth.Que, "Trip*")
End Function

