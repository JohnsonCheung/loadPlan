VERSION 1.0 CLASS
BEGIN
  MultiUse = -1  'True
END
Attribute VB_Name = "Pj"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = False
Attribute VB_Exposed = True
Option Explicit
Public A_Pj As VBProject
Sub Init(Pj As VBProject)
Set A_Pj = Pj
End Sub
Function Md(Idx) As Md
Dim O As New Md
If IsStr(Idx) Then
    O.Init A_Pj.VBComponents(Idx).CodeModule
    Set Md = O
    Exit Function
End If
If IsNum(Idx) Then
    Idx = CInt(Idx)
    Dim I As VBComponent
    Dim J%
    For Each I In A_Pj.VBComponents
        If I.Type = vbext_ct_StdModule Or I.Type = vbext_ct_ClassModule Then
            If J = Idx Then
                Set O = New Md
                O.Init I.CodeModule
                Set Md = O
                Exit Function
            End If
            J = J + 1
        End If
    Next
End If
End Function
Function ExpPth$()

End Function
Function Nm$()
Nm = A_Pj.Name
End Function
Function Pth$()
Pth = FfnPth(A_Pj.Filename)
End Function
Function MdAy() As Md()
Dim O() As Md, M As Md
Dim I As VBComponent
For Each I In A_Pj.VBComponents
    If I.Type = vbext_ct_StdModule Or I.Type = vbext_ct_ClassModule Then
        Set M = New Md
        M.Init I.CodeModule
        PushObj O, M
    End If
Next
MdAy = O
End Function
Function Export() As PjExpr
Set Export = Expr.Export
End Function
Function Fn$()
Fn = FfnFn(A_Pj.Filename)
End Function
Function Expr() As PjExpr
Dim O As New PjExpr
O.Init Me
Set Expr = O
End Function
Function MdAn() As String()
Dim A() As Md, J%
Dim O$()
A = MdAy
For J = 0 To UB(A)
    Push O, A(J).A_Md.Name
Next
MdAn = O
End Function

