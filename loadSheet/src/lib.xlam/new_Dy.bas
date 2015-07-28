Attribute VB_Name = "new_Dy"
Option Explicit

Function NewDyByLpAp(Lp$, ParamArray Ap())
Dim A$(), O As New Dy, M As New Dictionary, J%
A = Split(Lp, " ")
For J = 0 To UB(A)
    M.Add A(J), Ap(J)
Next
Set NewDyByLpAp = NewDy(M)
End Function

Function NewDy(Dic As Dictionary) As Dy
Dim O As New Dy
Set NewDy = O.NewDy(Dic)
End Function

Function NewDyByFt(Ft$) As Dy
Dim A$(), M As New Dictionary, O As New Dy, J%
A = FtAy(Ft)
For J = 0 To UB(A)
    If Trim(A(J)) <> "" Then
        With StrBrk(A(J), "=")
            M.Add .S1, .S2
        End With
    End If
Next
Set NewDyByFt = NewDy(M)
End Function
