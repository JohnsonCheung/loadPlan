Attribute VB_Name = "VeVenv__Tst"
Option Explicit
Sub TstAll_VeVenv()
PjAy__Tst
End Sub
Private Sub PjAy__Tst()
Dim PjAy() As Pj
Dim I, Pj As Pj

PjAy = CurVenv.PjAy
For Each I In PjAy
    Set Pj = I
    Debug.Print I.A_Pj.Name
Next
Debug.Print "-----"
PjAy = CurVenv.PjAy(InclStdPj:=True)
For Each I In PjAy
    Set Pj = I
    Debug.Print I.A_Pj.Name
Next
End Sub
