Attribute VB_Name = "modLib"
Option Explicit
Public Fso As New FileSystemObject
Type S1S2
    S1 As String
    S2 As String
End Type
Function NowStr$()
NowStr = "'" & Format(Now(), "YYYY-MM-DD HH:MM:SS")
End Function

Sub PushObj(Ay, Obj As Object)
Dim N&
N = Sz(Ay)
ReDim Preserve Ay(N)
Set Ay(N) = Obj
End Sub
Function IsStr(V) As Boolean
IsStr = VarType(V) = vbString
End Function
Function IsNum(V) As Boolean
IsNum = True
If IsInt(V) Then Exit Function
If IsLng(V) Then Exit Function
If IsByt(V) Then Exit Function
If IsDbl(V) Then Exit Function
If IsSng(V) Then Exit Function
IsNum = True
End Function
Function IsInt(V) As Boolean
IsInt = VarType(V) = vbInteger
End Function

Function IsLng(V) As Boolean
IsLng = VarType(V) = vbLong
End Function

Function IsByt(V) As Boolean
IsByt = VarType(V) = vbByte
End Function

Function IsDbl(V) As Boolean
IsDbl = VarType(V) = vbDouble
End Function

Function IsSng(V) As Boolean
IsSng = VarType(V) = vbSingle
End Function

Sub WbWsNmAy__Tst()
Dim Act$()
Act = WbWsNmAy(ThisWorkbook)
Debug.Assert Sz(Act) = 1
Debug.Assert Act(0) = "Sheet1"
End Sub

Function IsSfx(Sfx$, S$) As Boolean
IsSfx = Right(S, Len(Sfx)) = Sfx
End Function

Private Sub IsSfx__Tst()
Debug.Assert IsSfx("ab", "aab")
Debug.Assert Not IsSfx("Ab", "aab")
Debug.Assert Not IsSfx("ba", "aab")
End Sub

Private Sub IsPfx__Tst()
Debug.Assert IsPfx("aa", "aab")
Debug.Assert Not IsPfx("Aa", "aab")
Debug.Assert Not IsPfx("ba", "aab")
End Sub
Function IsPfx(Pfx$, S$) As Boolean
IsPfx = Left(S, Len(Pfx)) = Pfx
End Function
Function WbWsNmAy(Wb As Workbook) As String()
Dim J%, O$(), Ws As Worksheet
For Each Ws In Wb.Sheets
    Push O, Ws.Name
Next
WbWsNmAy = O
End Function
Sub PthAyFnLik__Tst()
Dim Act$()
Act = PthAyFnLik("C:\", "Pytho##")
Debug.Assert Sz(Act) = 2
Debug.Assert Act(0) = "Python27"
Debug.Assert Act(0) = "Python33"
End Sub
Function PthAyFnLik(Pth$, Lik$) As String()
Dim O$(), M$
M = Dir(Pth)
While M <> ""
    If M Like Lik Then Push O, M
    M = Dir
Wend
PthAyFnLik = O
End Function
Function Sz&(Ay)
On Error Resume Next
Sz = UBound(Ay) + 1
End Function
Function WbNew() As Workbook
Set WbNew = Application.Workbooks.Add
End Function
Sub PthOpn(Pth$)
Shell "explorer """ & Pth & """"
End Sub

Function WsNew(Optional WsNm$ = "Sheet1") As Worksheet
Dim Wb  As Workbook, Ws As Worksheet
Set Wb = WbNew
Wb.Application.DisplayAlerts = False
Set Ws = Wb.Sheets(2): Ws.Delete
Set Ws = Wb.Sheets(2): Ws.Delete
Wb.Application.DisplayAlerts = True
Set WsNew = Wb.Sheets(1)
End Function
Function FfnFnn$(Ffn$)
FfnFnn = FfnCutExt(FfnFn(Ffn))
End Function
Sub TstFtBrw()
Open "C:\Temp\a.txt" For Output As #1
Print #1, "asdfsdf"
Print #1, "lsdkfjsdlf"
Close #1
FtBrw "C:\Temp\a.txt"
End Sub
Function AyIsEq(A1$(), A2$()) As Boolean
Dim N&, J&
N = Sz(A1)
If N <> Sz(A2) Then Exit Function
For J = 0 To N - 1
    If A1(J) <> A2(J) Then Exit Function
Next
AyIsEq = True
End Function
Sub Pass(PgmNm$)
Debug.Print "Pass: " & PgmNm
End Sub
Function IsNothing(Obj) As Boolean
IsNothing = TypeName(Obj) = "Nothing"
End Function
Sub FtBrw(Ft$)
Shell "NotePad """ & Ft & """"
End Sub
Function RgePutImgFfn(Rge As Range, ImgFfn$, Optional PicNm$ = "") As Shape
If Not IsFile(ImgFfn) Then Exit Function
Dim O As Shape, Ws As Worksheet
Set Ws = Rge.Worksheet
Dim L!, T!, W!, H!
L = Rge.Left + 2
T = Rge.Top + 2
W = 200
H = 200
Set O = Ws.Shapes.AddPicture(ImgFfn, msoFalse, msoCTrue, L, T, W, H)
If PicNm <> "" Then O.Name = PicNm
Set RgePutImgFfn = O
End Function

Function ShpNxtRno&(Shp As Shape, Rge As Range)
'Return the next Rno after the shape
Dim H!, R As Range, J%
H = Shp.Height
For J = 1 To 10000 ' Assume max 10000 rows
    Set R = Rge(J, 1)
    H = H - R.RowHeight
    If H < 0 Then
        ShpNxtRno = Rge.Row + J
        Exit Function
    End If
Next
End Function


Function TakAftLastX$(S, X$, Optional AlsoTakX As Boolean = False, Optional ReturnS_IfNoX As Boolean)
Dim P%, L%
P = InStrRev(S, X)
If P = 0 Then
    If ReturnS_IfNoX Then TakAftLastX = S
    Exit Function
End If
If Not AlsoTakX Then L = Len(X)
TakAftLastX = Mid(S, P + L)
End Function

Function UB&(Ay)
UB = Sz(Ay) - 1
End Function
Function FfnFn$(Ffn$)
FfnFn = Dft(TakAftLastX(Ffn, "\"), Ffn$)
End Function
Function Dft(V$, pDft$)
Dft = IIf(V = "", pDft, V)
End Function

Function PthAyFdr(Pth$, Optional FSpec$ = "*.*") As String()
Dim O$(), M$
M = Dir(Pth & FSpec, vbDirectory)
While M <> ""
    If M <> "." Then
        If M <> ".." Then
            If IsPth(Pth & M) Then
                Push O, M
            End If
        End If
    End If
    M = Dir
Wend
PthAyFdr = O
End Function
Sub AyDmp(Ay)
Dim J&
For J = 0 To UB(Ay)
    Debug.Print J & ":[" & Ay(J) & "]"
Next
End Sub
Function AyLasEle(Ay)
AyLasEle = Ay(UB(Ay))
End Function
Sub FxOpn(Fx$)
Application.Workbooks.Open Fx
End Sub
Function FtAy(Ft$) As String()
Dim A$
A = Fso.OpenTextFile(Ft).ReadAll
FtAy = Split(A, vbCrLf)
End Function
Function IsFile(File$) As Boolean
IsFile = Fso.FileExists(File)
End Function
Function IsPth(Pth$) As Boolean
IsPth = Fso.FolderExists(Pth)
End Function

Sub Push(Ay, I)
Dim N&
N = Sz(Ay)
ReDim Preserve Ay(N)
Ay(N) = I
End Sub

Function PthAyFn(Pth, Optional FSpec$ = "*.*") As String()
Dim O$(), M$
M = Dir(Pth & FSpec)
While M <> ""
    Push O, M
    M = Dir
Wend
PthAyFn = O
End Function

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
Sub ClsTextStream(S As TextStream)
On Error Resume Next
S.Close
End Sub
Sub PthCrtEachSeg(Pth$)
Dim A$(), iPth$, J%

A = Split(Pth, "\")
iPth = A(0) & "\"
For J = 1 To UB(A) - 1
    iPth = iPth & A(J) & "\"
    If Dir(iPth, vbDirectory) = "" Then MkDir iPth
Next
End Sub
Function PthCur$()
PthCur = FfnPth(ThisWorkbook.FullName)
End Function
Function PthNorm$(Pth, Optional PthBase$ = "")
'Aim: 'Set' Pth and 'Norm' it
'     Where 'Set'  is If prefix of Pth .\ or ..\, add NonBlank(PthBase,CurDbPth) to Pth
'     Where 'Norm' is to replace \.\ to \ and \..\ to remove 1 lvl in Pth
If Left(Pth, 2) = ".\" Or Left(Pth, 3) = "..\" Then
    If PthBase = "" Then
        Pth = PthCur & Pth
    Else
        Pth = PthBase & Pth
    End If
End If
Pth = Replace(Pth, "\.\", "\")
Dim mP%, mA$, mB$, Brk As S1S2
mP = InStr(Pth, "\..\")
While mP > 0
    Brk = StrBrk(Pth, "\..\")
    mA = Brk.S1
    mB = Brk.S2
    
    Pth = TakBefChrRev(mA, "\", pKeepChr:=True) & mB
    mP = InStr(Pth, "\..\")
Wend
PthNorm = Pth
End Function

Function RmvLastChr$(S$)
RmvLastChr = Left(S, Len(S) - 1)
End Function
Sub PthDlt__Tst()
Const Pth = "C:\Temp\AA"
MkDir Pth
Open Pth & "\A.txt" For Output As #1
Print #1, "aa"
Print #1, "aa"
Print #1, "aa"
Print #1, "aa"
Close #1
Dim F%
F = FreeFile(1)
Dim L$
Open "C:\Temp\AA\A.txt" For Input As #F
While Not EOF(F)
    Line Input #F, L
Wend
Close #F
PthDlt Pth, Force:=True
End Sub
Function RmvSlashAtEnd$(Pth$)
If Right(Pth, 1) = "\" Then
    RmvSlashAtEnd = RmvLastChr(Pth)
Else
    RmvSlashAtEnd = Pth
End If
End Function

Sub PthDlt(Pth$, Optional Force As Boolean)
If Not IsPth(Pth) Then Exit Sub
Dim A$
A = RmvSlashAtEnd(Pth)
Fso.DeleteFolder A, Force:=Force
End Sub

Sub PthDlt_Fdr(Pth$)
If Right(Pth, 1) <> "\" Then Err.Raise 1, , "Pth must end with \"
Dim AyFdr$(), J%
AyFdr = PthAyFdr(Pth)
For J = 0 To UB(AyFdr)
    PthDlt Pth & AyFdr(J)
Next
End Sub

Sub PthDlt_File(Pth$)
If Right(Pth, 1) <> "\" Then Err.Raise 1, , "Pth must end with \"
Dim AyFn$(), J%
AyFn = PthAyFn(Pth)
For J = 0 To UB(AyFn)
    FfnDlt Pth & AyFn(J)
Next
End Sub

Function TimStmp()
Static Sno&
TimStmp = Format(Now, "YYYY-MM-DD HHMMSS") & " " & Sno
Sno = Sno + 1
End Function

Function PthMov$(Pth$, PthTo$)
On Error GoTo X
Dim A$, B$
A = RmvSlashAtEnd(Pth)
B = RmvSlashAtEnd(PthTo)
Fso.MoveFolder A, B
Exit Function
X: PthMov = Err.Description
End Function

Sub FfnDlt(Ffn$)
On Error Resume Next
Kill Ffn
End Sub

Function TakBefChrRev$(P, pChr$, Optional pKeepChr As Boolean = False)
Dim mP%: mP = InStrRev(P, pChr): If mP = 0 Then Exit Function
If pKeepChr Then TakBefChrRev = Left(P, mP - 1 + Len(pChr)): Exit Function
TakBefChrRev = Left(P, mP - 1)
End Function

Function WsPutImgFfn(Ws As Worksheet, ImgFfn$, T!, L!, H!, W!, Optional PicNm$ = "") As Shape
If Dir(ImgFfn) = "" Then Exit Function
Dim O As Shape
Set O = Ws.Shapes.AddPicture(ImgFfn, msoFalse, msoCTrue, L, T, W, H)
If PicNm <> "" Then O.Name = PicNm
Set WsPutImgFfn = O
End Function

Function StrBrk(S, BrkChr$, Optional NoTrim As Boolean = False) As S1S2
Dim mP%, O1$, O2$
mP = InStr(S, BrkChr): If mP = 0 Then Err.Raise 1, "StrBrk", "S[" & S & "] does contains BrkChr[" & BrkChr & "].  Cannot break into 2."
Dim mL%
mL = Len(BrkChr)
If NoTrim Then
    O1 = Left(S, mP - 1)
    O2 = Mid(S, mP + mL)
Else
    O1 = Trim(Left(S, mP - 1))
    O2 = Trim(Mid(S, mP + mL))
End If
StrBrk.S1 = O1
StrBrk.S2 = O2
End Function

Sub RgeSetBdrGrid(Rge As Range)
Rge.BorderAround XlLineStyle.xlContinuous, xlMedium
With Rge.Borders(XlBordersIndex.xlInsideVertical)
    .LineStyle = xlContinuous
    .Weight = XlBorderWeight.xlThin
End With
With Rge.Borders(XlBordersIndex.xlInsideHorizontal)
    .LineStyle = xlContinuous
    .Weight = XlBorderWeight.xlThin
End With
Rge.BorderAround XlLineStyle.xlContinuous, xlMedium
With Rge.Borders(XlBordersIndex.xlInsideVertical)
    .LineStyle = xlContinuous
    .Weight = XlBorderWeight.xlThin
End With
With Rge.Borders(XlBordersIndex.xlInsideHorizontal)
    .LineStyle = xlContinuous
    .Weight = XlBorderWeight.xlThin
End With
End Sub
Private Sub StrSubStrCnt__Tst()
Dim A$
A = "lskdf\n\lskdf\nlskdfdf"
Debug.Assert StrSubStrCnt(A, "\n") = 2
End Sub
Function StrSubStrCnt%(Str$, SubStr$)
Dim P%, O%
P = InStr(Str, SubStr)
While P > 0
    O = O + 1
    P = InStr(P + Len(SubStr), Str, SubStr)
Wend
StrSubStrCnt = O
End Function

Function WbNmAy(Wb As Workbook) As String()
Dim O$(), J&
If Wb.Names.Count = 0 Then Exit Function
ReDim O$(Wb.Names.Count - 1)
For J = 1 To Wb.Names.Count
    O(J - 1) = Wb.Names(J).Name
Next
WbNmAy = O
End Function

Sub StrAyDmp(Ay$())
Dim J&
For J = 0 To UB(Ay)
    Debug.Print J & "=[" & Ay(J) & "]"
Next
End Sub

Sub WbNmDmp(Wb As Workbook, NmAy$())

End Sub
