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
Public A_VbPj As VBProject
Function NewPj(VbPj As VBProject) As Pj
Set A_VbPj = VbPj
Set NewPj = Me
End Function
Function Md(Idx) As Md
Dim O As New Md
If IsStr(Idx) Then
    Set Md = NewMd(A_VbPj.VBComponents(Idx).CodeModule)
    Exit Function
End If
If IsNum(Idx) Then
    Idx = CInt(Idx)
    Dim I As VBComponent
    Dim J%
    For Each I In A_VbPj.VBComponents
        If I.Type = vbext_ct_StdModule Or I.Type = vbext_ct_ClassModule Then
            If J = Idx Then
                Set Md = NewMd(I.CodeModule)
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
Nm = A_VbPj.Name
End Function
Function Pth$()
Pth = FfnPth(A_VbPj.Filename)
End Function
Function MdAy() As Md()
Dim O() As Md, M As Md
Dim I As VBComponent
For Each I In A_VbPj.VBComponents
    If I.Type = vbext_ct_StdModule Or I.Type = vbext_ct_ClassModule Then
        PushObj O, NewMd(I.CodeModule)
    End If
Next
MdAy = O
End Function
Function Export() As PjExpr
Set Export = Expr.Export
End Function

Function Fn$()
Fn = FfnFn(A_VbPj.Filename)
End Function

Function Expr() As PjExpr
Dim O As New PjExpr
Set Expr = O.NewPjExpr(Me)
End Function
Function MdAn() As String()
Dim A() As Md, J%
Dim O$()
A = MdAy
For J = 0 To UB(A)
    Push O, A(J).A_CdMd.Name
Next
MdAn = O
End Function

