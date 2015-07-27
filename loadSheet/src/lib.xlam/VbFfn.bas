Attribute VB_Name = "VbFfn"
Option Explicit

Function FfnPth$(Ffn)
Dim mP%: mP = InStrRev(Ffn, "\")
FfnPth = Left(Ffn, mP)
End Function

Function FfnAddFnSfx$(Ffn$, Sfx)
FfnAddFnSfx = FfnCutExt(Ffn) & Sfx & FfnExt(Ffn)
End Function

Function FfnBakNm$(Ffn$)
Dim J%, A
For J = 1 To 9999
    A = FfnAddFnSfx(Ffn, "-" & J)
    If Dir(A) = "" Then FfnBakNm = A: Exit Function
Next
End Function

Function FfnNewVer(Ffn$)
'Suppose Ffn is XXXX.XXX
'Return XXXX-n.XXX from if Ffn exist else return Ffn
'n is running# which not exist
If Dir(Ffn) = "" Then FfnNewVer = Ffn: Exit Function
Dim J%, O$
For J = 1 To 9999
    O = FfnAddFnSfx(Ffn, "-" & J)
    If Dir(O) = "" Then FfnNewVer = O: Exit Function
Next
Err.Raise 1, , "Too much NewVer.  File=[" & Ffn & "]"
End Function

Function FfnRenToBak$(Ffn$)
'Return "" if success else return error message
If Dir(Ffn) = "" Then Exit Function
Dim A$
A = FfnBakNm(Ffn)
On Error GoTo X
Name Ffn As A
Exit Function
X:
FfnRenToBak = Err.Description
End Function

Function FfnCutExt$(Ffn)
Dim P%: P = InStrRev(Ffn, ".")
If P = 0 Then
    FfnCutExt = Ffn
Else
    FfnCutExt = Left(Ffn, P - 1)
End If
End Function

Function FfnExt$(Ffn)
Dim mP%: mP = InStrRev(Ffn, ".")
If mP = 0 Then Exit Function
FfnExt = Mid(Ffn, mP)
End Function

Sub FfnDlt(Ffn$)
On Error Resume Next
Kill Ffn
End Sub

Function FfnFn$(Ffn$)
FfnFn = Dft(TakAftLastX(Ffn, "\"), Ffn$)
End Function

Function IsFile(Ffn$) As Boolean
IsFile = Fso.FileExists(Ffn)
End Function

Function FfnFnn$(Ffn$)
FfnFnn = FfnCutExt(FfnFn(Ffn))
End Function

