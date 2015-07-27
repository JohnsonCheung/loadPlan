Attribute VB_Name = "VbFt"
Option Explicit

Sub FtBrw(Ft$)
Shell "NotePad """ & Ft & """"
End Sub

Function FtAy(Ft$) As String()
Dim A$
A = Fso.OpenTextFile(Ft).ReadAll
FtAy = Split(A, vbCrLf)
End Function

Function TmpFt$()
Stop
End Function

