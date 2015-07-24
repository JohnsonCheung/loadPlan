Attribute VB_Name = "NewDy"
Option Explicit

Function ByLpAp(Lp$, ParamArray Ap())
Dim A$(), O As New Dy, M As New Dictionary, J%
A = Split(Lp, " ")
For J = 0 To UB(A)
    M.Add A(J), Ap(J)
Next
O.Init M
Set ByLpAp = O
End Function

Function Init(Dic As Dictionary) As Dy
Dim O As New Dy
O.Init Dic
Set Init = O
End Function

Function ByFt(Ft$) As Dy
Dim A$(), M As New Dictionary, O As New Dy, J%
A = FtAy(Ft)
For J = 0 To UB(A)
    If Trim(A(J)) <> "" Then
        With StrBrk(A(J), "=")
            M.Add .S1, .S2
        End With
    End If
Next
O.Init M
Set ByFt = O
End Function
