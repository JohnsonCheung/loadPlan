VERSION 1.0 CLASS
BEGIN
  MultiUse = -1  'True
END
Attribute VB_Name = "Venv"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = False
Attribute VB_Exposed = True
Option Explicit
Public A_Vbe As Vbe

Function NewVenv(Vbe As Vbe) As Venv
Set A_Vbe = Vbe
Set NewVenv = Me
End Function

Function Pj(PjNm$) As Pj
Dim O As New Pj
Set Pj = O.NewPj(A_Vbe.VBProjects(PjNm))
End Function

Function PjAy(Optional InclStdPj As Boolean = False) As Pj()
Dim O() As Pj, M As Pj, I As VBProject
For Each I In A_Vbe.VBProjects
    If Not IsStdPjNm(I.Name) Then
        PushObj O, NewPj(I)
    End If
Next
PjAy = O
End Function

Function ExpSrc() As Venv
Dim I, Pj As Pj
For Each I In PjAy
    Set Pj = I
    Pj.Export
Next
Set ExpSrc = Me
End Function
