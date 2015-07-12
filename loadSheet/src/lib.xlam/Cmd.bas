Attribute VB_Name = "Cmd"
Option Explicit

Sub ExpSrc(Optional PjNm$)
Dim Pj As Pj
    Set Pj = CurVenv.Pj(PjNm)
Dim M As Md, I
For Each I In Pj.MdAy
    Set M = I
    I.ExpSrc
Next
End Sub
