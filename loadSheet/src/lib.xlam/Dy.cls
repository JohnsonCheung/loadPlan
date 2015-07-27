VERSION 1.0 CLASS
BEGIN
  MultiUse = -1  'True
END
Attribute VB_Name = "Dy"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = False
Attribute VB_Exposed = True
Option Explicit
Public A_Dic As Dictionary

Function NewDy(Dic As Dictionary) As Dy
Set A_Dic = Dic
Set NewDy = Me
End Function

Sub Assign(Lp$, ParamArray OAp())
Dim Ay$()
    Ay = Split(Lp, " ")
Dim J%
For J = 0 To UB(Ay)
    OAp(J) = A_Dic(Ay(J))
Next
End Sub

Function UnEscLF() As Dy
Dim K$(), J%
K = A_Dic.Keys
For J = 0 To UB(K)
    A_Dic(K(J)) = UnEscLF(A_Dic(K(J)))
Next
Set UnEscLF = Me
End Function

Function EscLF() As Dy
Dim K$(), J%
K = A_Dic.Keys
For J = 0 To UB(K)
    A_Dic(K(J)) = EscLF(A_Dic(K(J)))
Next
Set UnEscLF = Me
End Function

Function Cnt&()
Cnt = A_Dic.Count
End Function

Sub Dmp()
Dim K$(), J%
K = A_Dic.Keys
For J = 0 To UB(K)
    Debug.Print K(J) & " = " & A_Dic(K(J))
Next
End Sub
