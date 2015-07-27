Attribute VB_Name = "VePj"
Option Explicit
Option Compare Text
Function Pj(PjNm$) As Pj
Set Pj = CurVenv.Pj(PjNm)
End Function

Sub ExpSrc(Optional PjNm$)
Dim Pj As Pj
    Set Pj = CurVenv.Pj(PjNm)
Dim M As Md, I
For Each I In Pj.MdAy
    Set M = I
    I.ExpSrc
Next
End Sub

Function IsStdPjNm(PjNm$) As Boolean
Select Case PjNm
Case "Excel", "Vba", "Office", "Scripting", "VbIde": IsStdPjNm = True
End Select
End Function

Function NewPj(VbPj As VBProject) As Pj
Dim O As New Pj
Set NewPj = O.NewPj(VbPj)
End Function
