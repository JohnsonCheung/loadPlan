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
Sub Init(Dic As Dictionary)
Set A_Dic = Dic
End Sub

Sub Assign(Lp$, ParamArray OAp())
Dim Ay$()
    Ay = Split(Lp, " ")
Dim J%
For J = 0 To UB(Ay)
    OAp(J) = A_Dic(Ay(J))
Next
End Sub

Function ReplBackSlashN_ByCrLf() As Dy
Dim K$(), J%
K = A_Dic.Keys
For J = 0 To UB(K)
    A_Dic(K(J)) = Replace(A_Dic(K(J)), "\n", vbCrLf)
Next
Set ReplBackSlashN_ByCrLf = Me
End Function

Function Cnt&()
Cnt = A_Dic.Count
End Function
