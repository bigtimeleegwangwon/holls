

<script>
const GRADE = {
  Q:   { short: "품질 등급",   full: "품질 등급제 대상",        rank: 0 },
  D:   { short: "개발 등급",   full: "개발 등급제만 대상",      rank: 1 },
  "-": { short: "등급 없음",   full: "등급제 미적용",           rank: 9 },
};

// 데이터에는 'QD-2' 처럼 뒤에 무언가 붙은 값도 올 수 있다. 앞자리로 가른다.
// QD는 품질 등급제를 포함하므로 품질 쪽으로 넣는다.
function gradeCode(v) {
  const s = String(v || "-").trim().toUpperCase();
  if (s.startsWith("Q")) return "Q";     // Q · QD 둘 다
  if (s.startsWith("D")) return "D";
  return "-";
}
const gradeOf = (v) => GRADE[gradeCode(v)];

// 원래 코드는 잃지 않는다 — 툴팁에서 보여 준다.
function gradeRaw(v) {
  const s = String(v || "-").trim().toUpperCase();
  return s.startsWith("QD") ? "QD" : s.startsWith("Q") ? "Q"
       : s.startsWith("D") ? "D" : "-";
}
const RAW_FULL = { QD: "품질/개발 등급제", Q: "품질 등급제", D: "개발 등급제", "-": "등급제 미적용" };

const gradeLabel = (v) => gradeOf(v).short;
const gradeTitle = (v) => {
  const r = gradeRaw(v);
  return r === "-" ? RAW_FULL["-"] : `${r} · ${RAW_FULL[r]}`;
};
const gradeRank  = (v) => gradeOf(v).rank;
const gradeClass = (v) => ({ Q: "qd", D: "dv", "-": "pl" })[gradeCode(v)];
</script>

