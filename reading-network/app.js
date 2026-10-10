// 색상 매핑 (4가지 고정색)
const categoryColorMap = {
  scarlet: '#FF2400',
  amber: '#FFBF00',
  emerald: '#50C878',
  turquoise: '#00CDE1'
};

const categoryNames = {
  scarlet: 'Scarlet',
  amber: 'Amber',
  emerald: 'Emerald',
  turquoise: 'Turquoise'
};

// 앱 상태
let graphData = { nodes: [], edges: [] };
let filteredData = { nodes: [], edges: [] };
let selectedCategories = new Set(['scarlet', 'amber', 'emerald', 'turquoise']);
let selectedLayers = new Set(['서사전개', '철학기초', '인물관계', '은유확장']);
let searchQuery = '';
let selectedNodeId = null;
let nodeMap = {};

// SVG 및 시뮬레이션 인스턴스
let svg, simulation, g, nodeSelection, linkSelection, timelineAxis;
let xScale, rScale;
let margin = { top: 20, right: 80, bottom: 60, left: 20 };


// 초기화
async function init() {
  try {
    const response = await fetch('data.json');
    if (!response.ok) throw new Error('data.json 로드 실패');
    graphData = await response.json();

    if (!graphData.nodes) {
      graphData = { nodes: [] };
    }

    // 노드 맵 생성 (빠른 조회용)
    nodeMap = {};
    graphData.nodes.forEach(node => {
      nodeMap[node.id] = node;
    });

    initializeControls();
    initializeSVG();  // SVG 및 g 엘리먼트 먼저 생성
    applyFilters();   // 이 안에서 renderGraph() 호출
    renderTable();
  } catch (error) {
    console.error('초기화 오류:', error);
  }
}

// 테마 토글 라벨 업데이트
function updateThemeToggleLabel() {
  const themeToggle = document.getElementById('themeToggle');
  const currentTheme = document.documentElement.dataset.theme || 'dark';
  themeToggle.textContent = currentTheme === 'dark' ? '☀️' : '🌙';
}

// 컨트롤 UI 초기화
function initializeControls() {
  document.getElementById('searchInput').addEventListener('input', (e) => {
    searchQuery = e.target.value.toLowerCase();
    applyFilters();
  });

  document.getElementById('toggleGraphView').addEventListener('click', () => {
    showGraphView();
  });

  document.getElementById('toggleListView').addEventListener('click', () => {
    showListView();
  });

  // 테마 토글
  document.getElementById('themeToggle').addEventListener('click', () => {
    const currentTheme = document.documentElement.dataset.theme || 'dark';
    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
    document.documentElement.dataset.theme = newTheme;
    localStorage.setItem('theme', newTheme);
    updateThemeToggleLabel();
  });

  updateThemeToggleLabel();

  const isMobile = window.matchMedia('(max-width: 768px)').matches;

  // 모바일 범주 필터 드롭다운 초기화
  const filterDetails = document.getElementById('filterDetails');
  if (filterDetails) {
    // 기본 열림/닫힘 상태 설정
    filterDetails.open = !isMobile;

    // 모바일에서 드롭다운 바깥 클릭 시 닫기
    document.addEventListener('click', (e) => {
      if (isMobile && filterDetails.open && !filterDetails.contains(e.target)) {
        filterDetails.open = false;
      }
    });
  }

  // 범례(범주) 패널 접기/펼치기 초기화
  const legendDetails = document.getElementById('legendDetails');
  if (legendDetails) {
    // 모바일에서는 기본적으로 접힌 상태로 시작 (그래프를 가리지 않도록)
    legendDetails.open = !isMobile;
  }

  // 4색 고정 필터
  const filterContainer = document.getElementById('categoryFilters');
  filterContainer.innerHTML = '';
  Object.keys(categoryColorMap).forEach(cat => {
    const label = document.createElement('label');
    label.className = 'category-filter';

    const checkbox = document.createElement('input');
    checkbox.type = 'checkbox';
    checkbox.checked = true;
    checkbox.value = cat;
    checkbox.addEventListener('change', (e) => {
      if (e.target.checked) {
        selectedCategories.add(cat);
      } else {
        selectedCategories.delete(cat);
      }
      applyFilters();
    });

    const text = document.createElement('label');
    text.style.margin = '0';
    text.textContent = categoryNames[cat];

    const colorDot = document.createElement('span');
    colorDot.style.display = 'inline-block';
    colorDot.style.width = '12px';
    colorDot.style.height = '12px';
    colorDot.style.borderRadius = '2px';
    colorDot.style.backgroundColor = categoryColorMap[cat];
    colorDot.style.marginRight = '0.3rem';

    label.appendChild(checkbox);
    label.appendChild(colorDot);
    label.appendChild(text);
    filterContainer.appendChild(label);
  });

  // Layer 필터 (동적)
  const layerContainer = document.getElementById('layerFilters');
  if (layerContainer) {
    layerContainer.innerHTML = '';
    ['서사전개', '철학기초', '인물관계', '은유확장'].forEach(layer => {
      const label = document.createElement('label');
      label.className = 'category-filter';

      const checkbox = document.createElement('input');
      checkbox.type = 'checkbox';
      checkbox.checked = true;
      checkbox.value = layer;
      checkbox.addEventListener('change', (e) => {
        if (e.target.checked) {
          selectedLayers.add(layer);
        } else {
          selectedLayers.delete(layer);
        }
        applyFilters();
      });

      const text = document.createElement('label');
      text.style.margin = '0';
      text.textContent = layer;

      label.appendChild(checkbox);
      label.appendChild(text);
      layerContainer.appendChild(label);
    });
  }

  document.getElementById('closePanel').addEventListener('click', closeDetailPanel);
}

// 필터 적용
function applyFilters() {
  filteredData.nodes = graphData.nodes.filter(node => {
    const categoryMatch = selectedCategories.has(node.category);
    const searchMatch = node.label.toLowerCase().includes(searchQuery);
    return categoryMatch && searchMatch;
  });

  const nodeIds = new Set(filteredData.nodes.map(n => n.id));

  // 엣지 필터링 (노드, layer 기반)
  filteredData.edges = (graphData.edges || []).filter(edge => {
    const sourceId = edge.source.id || edge.source;
    const targetId = edge.target.id || edge.target;
    const nodeMatch = nodeIds.has(sourceId) && nodeIds.has(targetId);
    const layerMatch = selectedLayers.has(edge.layer);
    return nodeMatch && layerMatch;
  });

  renderGraph();
}

// SVG 초기화
function initializeSVG() {
  const container = document.getElementById('graphView');
  const width = container.clientWidth;
  const height = container.clientHeight;

  d3.select('#networkGraph').selectAll('*').remove();

  svg = d3.select('#networkGraph')
    .attr('width', width)
    .attr('height', height);

  g = svg.append('g');

  // 스케일 설정
  const timeOrders = graphData.nodes.map(n => n.timeOrder);
  const minTime = timeOrders.length > 0 ? Math.min(...timeOrders) : 1;
  const maxTime = timeOrders.length > 0 ? Math.max(...timeOrders) : 5;

  xScale = d3.scaleLinear()
    .domain([Math.max(0, minTime - 0.5), maxTime + 0.5])
    .range([margin.left, width - margin.right]);

  // radius(0~3)를 y 위치로 매핑 (중심선 기준 상하 대칭)
  const centerY = height / 2;
  const radiusHeight = (height - margin.top - margin.bottom) / 2 / 3.5;
  rScale = d3.scaleLinear()
    .domain([0, 3])
    .range([0, radiusHeight * 3]);

  // 반경 배경 그리기
  drawRadiusBands(width, height, centerY, radiusHeight);

  // 줌 동작
  const zoom = d3.zoom().on('zoom', (event) => {
    g.attr('transform', event.transform);
  });
  svg.call(zoom);

  // 시뮬레이션 설정 (x 고정, y는 반경 기반 수평 밴드)
  simulation = d3.forceSimulation()
    .force('link', d3.forceLink()
      .id(d => d.id)
      .distance(80)
      .strength(0.4))
    .force('charge', d3.forceManyBody().strength(-150))
    .force('forceX', d3.forceX(d => xScale(d.timeOrder))
      .strength(0.85))
    .force('forceY', d3.forceY(d => centerY - rScale(d.radius || 0))
      .strength(0.3))
    .force('collision', d3.forceCollide().radius(15));

  // 타임라인 축 그리기
  drawTimelineAxis(width, height);

  window.addEventListener('resize', () => {
    const newWidth = container.clientWidth;
    const newHeight = container.clientHeight;
    svg.attr('width', newWidth).attr('height', newHeight);
    xScale.range([margin.left, newWidth - margin.right]);
    const newCenterY = newHeight / 2;
    const newRadiusHeight = (newHeight - margin.top - margin.bottom) / 2 / 3.5;
    rScale.range([0, newRadiusHeight * 3]);
    drawRadiusBands(newWidth, newHeight, newCenterY, newRadiusHeight);
    simulation
      .force('forceX', d3.forceX(d => xScale(d.timeOrder)).strength(0.85))
      .force('forceY', d3.forceY(d => newCenterY - rScale(d.radius || 0)).strength(0.3));
    drawTimelineAxis(newWidth, newHeight);
  });
}

// 반경 밴드 그리기 (수평 점선 + 라벨)
function drawRadiusBands(width, height, centerY, radiusHeight) {
  g.selectAll('.radius-band').remove();
  g.selectAll('.radius-label').remove();

  const radiusLabels = ['Radius 0 (Core)', 'Radius 1', 'Radius 2', 'Radius 3 (Periphery)'];

  for (let r = 0; r <= 3; r++) {
    const y = centerY - r * radiusHeight;

    // 상측 점선
    g.append('line')
      .attr('class', 'radius-band')
      .attr('x1', margin.left)
      .attr('x2', width - margin.right)
      .attr('y1', y)
      .attr('y2', y)
      .attr('stroke', 'var(--gridline)')
      .attr('stroke-dasharray', '4,4')
      .attr('opacity', 0.3)
      .attr('stroke-width', 1);

    // 하측 점선 (중심선 아래)
    const yBelow = centerY + r * radiusHeight;
    g.append('line')
      .attr('class', 'radius-band')
      .attr('x1', margin.left)
      .attr('x2', width - margin.right)
      .attr('y1', yBelow)
      .attr('y2', yBelow)
      .attr('stroke', 'var(--gridline)')
      .attr('stroke-dasharray', '4,4')
      .attr('opacity', 0.3)
      .attr('stroke-width', 1);

    // 반경 라벨 (우측)
    g.append('text')
      .attr('class', 'radius-label')
      .attr('x', width - margin.right + 5)
      .attr('y', y + 4)
      .attr('font-size', '10px')
      .attr('fill', 'var(--text-muted)')
      .attr('text-anchor', 'start')
      .text(radiusLabels[r]);
  }
}

// 타임라인 축 그리기
function drawTimelineAxis(width, height) {
  // 기존 축 제거
  g.selectAll('.timeline-axis').remove();

  const axisY = height - margin.bottom + 20;

  // minTime/maxTime 계산 (initializeSVG의 xScale 설정과 일치)
  const timeOrders = graphData.nodes.map(n => n.timeOrder);
  const minTime = timeOrders.length > 0 ? Math.min(...timeOrders) : 1;
  const maxTime = timeOrders.length > 0 ? Math.max(...timeOrders) : 5;

  // 축 선
  g.append('line')
    .attr('class', 'timeline-axis')
    .attr('x1', xScale(Math.max(0, minTime - 0.5)))
    .attr('x2', xScale(maxTime + 0.5))
    .attr('y1', axisY)
    .attr('y2', axisY)
    .attr('stroke-width', 1);

  // 눈금과 레이블 (minTime부터 maxTime까지)
  for (let i = Math.ceil(minTime); i <= maxTime; i++) {
    const x = xScale(i);

    // 눈금
    g.append('line')
      .attr('class', 'timeline-axis')
      .attr('x1', x)
      .attr('x2', x)
      .attr('y1', axisY)
      .attr('y2', axisY + 5)
      .attr('stroke-width', 1);

    // 레이블
    g.append('text')
      .attr('class', 'timeline-axis')
      .attr('x', x)
      .attr('y', axisY + 20)
      .attr('text-anchor', 'middle')
      .attr('font-size', '12px')
      .text(i);
  }
}

// 그래프 렌더링
function renderGraph() {
  // 링크 (곡선 경로)
  linkSelection = g.selectAll('.link').data(filteredData.edges, d => d.id);
  linkSelection.exit().remove();

  const linkEnter = linkSelection.enter().append('path')
    .attr('class', 'link');

  linkSelection = linkEnter.merge(linkSelection)
    .attr('stroke-width', d => {
      const baseWidth = 1.5;
      const weight = d.weight || 1;
      return baseWidth + weight * 0.3;
    });

  linkSelection
    .on('mouseenter', (event, d) => showLinkTooltip(event, d))
    .on('mouseleave', hideLinkTooltip);

  // 노드
  nodeSelection = g.selectAll('.node').data(filteredData.nodes, d => d.id);
  nodeSelection.exit().remove();

  const nodeEnter = nodeSelection.enter().append('circle')
    .attr('class', 'node')
    .attr('r', 5)
    .attr('fill', d => categoryColorMap[d.category])
    .attr('stroke', d => calculateStrokeColor(d.category))
    .attr('stroke-width', 1.5)
    .call(d3.drag()
      .on('start', dragStarted)
      .on('drag', dragged)
      .on('end', dragEnded));

  nodeSelection = nodeEnter.merge(nodeSelection);

  nodeSelection.on('click', (event, d) => {
    event.stopPropagation();
    showDetailPanel(d);
    highlightNode(d.id);
  });

  // 노드 라벨 (텍스트)
  let labelSelection = g.selectAll('.node-label').data(filteredData.nodes, d => `label-${d.id}`);
  labelSelection.exit().remove();

  const labelEnter = labelSelection.enter().append('text')
    .attr('class', 'node-label');

  labelSelection = labelEnter.merge(labelSelection)
    .attr('text-anchor', 'middle')
    .text(d => d.label);

  nodeSelection
    .on('mouseenter', (event, d) => {
      const outgoingIds = new Set(filteredData.edges.filter(e => e.source.id === d.id || e.source === d.id).map(e => e.target.id || e.target));
      const incomingIds = new Set(filteredData.edges.filter(e => e.target.id === d.id || e.target === d.id).map(e => e.source.id || e.source));

      linkSelection.classed('link-outgoing', edge => {
        const sourceId = edge.source.id || edge.source;
        return sourceId === d.id;
      })
      .classed('link-incoming', edge => {
        const targetId = edge.target.id || edge.target;
        return targetId === d.id;
      })
      .classed('faded', edge => {
        const sourceId = edge.source.id || edge.source;
        const targetId = edge.target.id || edge.target;
        return sourceId !== d.id && targetId !== d.id;
      });

      labelSelection.classed('highlighted', node => {
        return node.id === d.id || outgoingIds.has(node.id) || incomingIds.has(node.id);
      });
    })
    .on('mouseleave', () => {
      linkSelection.classed('link-outgoing', false)
        .classed('link-incoming', false)
        .classed('faded', false);
      labelSelection.classed('highlighted', false);
    });

  simulation.nodes(filteredData.nodes);
  simulation.force('link').links(filteredData.edges);
  simulation.alpha(1).restart();

  simulation.on('tick', () => {
    linkSelection.attr('d', linkPath);

    nodeSelection
      .attr('cx', d => d.x)
      .attr('cy', d => d.y);

    labelSelection
      .attr('x', d => d.x)
      .attr('y', d => d.y - 12);
  });

  updateLegend();

  svg.on('click', () => {
    closeDetailPanel();
    nodeSelection.classed('highlighted', false);
    labelSelection.classed('highlighted', false);
    linkSelection.classed('link-outgoing', false)
      .classed('link-incoming', false)
      .classed('faded', false);
  });
}

// 곡선 경로 생성 (3차 베지어 곡선)
function linkPath(d) {
  const x0 = d.source.x;
  const y0 = d.source.y;
  const x1 = d.target.x;
  const y1 = d.target.y;

  // 중간 제어점을 이용한 곡선 (source와 target의 x 중점 부근에서 y 방향으로 벗어남)
  const midX = (x0 + x1) / 2;
  const controlX = midX;
  const controlY = (y0 + y1) / 2;

  return `M${x0},${y0}C${controlX},${y0} ${controlX},${y1} ${x1},${y1}`;
}

// 테두리 색상 결정 (밝은 색에 대한 명도 보강)
function calculateStrokeColor(category) {
  if (category === 'amber' || category === 'turquoise') {
    return '#0b0b0b';  // 밝은 색이면 진한색
  }
  return '#f5f5f5';  // 어두운 색이면 밝은색
}

// 범례 업데이트
function updateLegend() {
  const legendContainer = document.getElementById('legendItems');
  legendContainer.innerHTML = '';

  Object.keys(categoryColorMap).forEach(cat => {
    if (!selectedCategories.has(cat)) return;

    const item = document.createElement('div');
    item.className = 'legend-item';

    const dot = document.createElement('div');
    dot.className = 'legend-dot';
    dot.style.backgroundColor = categoryColorMap[cat];

    const label = document.createElement('span');
    label.textContent = categoryNames[cat];

    item.appendChild(dot);
    item.appendChild(label);
    legendContainer.appendChild(item);
  });
}

// 테이블 렌더링
function renderTable() {
  const tableBody = document.getElementById('tableBody');
  tableBody.innerHTML = '';

  const sortedNodes = [...filteredData.nodes].sort((a, b) => a.timeOrder - b.timeOrder);

  sortedNodes.forEach(node => {
    const row = document.createElement('tr');
    row.style.cursor = 'pointer';
    row.addEventListener('click', () => showDetailPanel(node));

    const colorCircle = `<span style="display:inline-block; width:10px; height:10px; border-radius:50%; background-color:${categoryColorMap[node.category]}; margin-right:0.5rem;"></span>`;

    // 나가는 연결 찾기
    const outgoingEdges = filteredData.edges.filter(e => {
      const sourceId = e.source.id || e.source;
      return sourceId === node.id;
    });

    let relationsText = '—';
    if (outgoingEdges.length > 0) {
      relationsText = outgoingEdges.map(edge => {
        const targetId = edge.target.id || edge.target;
        const targetNode = nodeMap[targetId];
        const targetLabel = targetNode ? targetNode.label : targetId;
        return `→ ${targetLabel}${edge.relation ? ` (${edge.relation})` : ''}`;
      }).join('; ');
    }

    row.innerHTML = `
      <td><strong>${node.label}</strong></td>
      <td>${colorCircle}${categoryNames[node.category]}</td>
      <td>${node.timeOrder}</td>
      <td>${node.radius || 0}</td>
      <td>${node.chapter || '—'} / ${node.page || '—'}</td>
      <td style="font-size: 0.85rem; color: var(--text-muted);">${relationsText}</td>
    `;
    tableBody.appendChild(row);
  });
}

// 노드 강조
function highlightNode(nodeId) {
  nodeSelection.classed('highlighted', d => d.id === nodeId);
  const relatedEdges = filteredData.edges.filter(e => {
    const sourceId = e.source.id || e.source;
    const targetId = e.target.id || e.target;
    return sourceId === nodeId || targetId === nodeId;
  });
  linkSelection.classed('faded', edge => {
    const sourceId = edge.source.id || edge.source;
    const targetId = edge.target.id || edge.target;
    return !relatedEdges.some(re => {
      const reSourceId = re.source.id || re.source;
      const reTargetId = re.target.id || re.target;
      return reSourceId === sourceId && reTargetId === targetId;
    });
  });
}

// 상세 패널 표시
function showDetailPanel(node) {
  document.getElementById('detailLabel').textContent = node.label;
  const categoryNameEl = document.getElementById('detailCategory');
  categoryNameEl.textContent = categoryNames[node.category];
  categoryNameEl.style.backgroundColor = categoryColorMap[node.category];
  categoryNameEl.style.color = calculateTextColor(node.category);

  document.getElementById('detailLocation').textContent =
    `${node.timeOrder}번째 사건 | ${node.chapter || '?'}장 / ${node.page || '?'}페이지`;

  const quoteEl = document.getElementById('detailQuote');
  quoteEl.style.display = node.quote ? 'block' : 'none';
  document.getElementById('detailQuote').textContent = node.quote || '';

  const noteEl = document.getElementById('detailNote');
  noteEl.style.display = node.note ? 'block' : 'none';
  document.getElementById('detailNote').textContent = node.note || '';

  document.getElementById('detailPanel').classList.remove('hidden');
  selectedNodeId = node.id;
}

// 텍스트 색상 결정 (밝은 배경이면 검은 글자)
function calculateTextColor(category) {
  if (category === 'amber' || category === 'turquoise') {
    return '#0b0b0b';
  }
  return 'white';
}

// 상세 패널 닫기
function closeDetailPanel() {
  document.getElementById('detailPanel').classList.add('hidden');
  nodeSelection.classed('highlighted', false);
  linkSelection.classed('faded', false);
  selectedNodeId = null;
}

// 링크 토울팁
function showLinkTooltip(event, d) {
  const tooltip = document.createElement('div');
  tooltip.className = 'link-tooltip';
  tooltip.textContent = d.relation || '관계';

  const { pageX, pageY } = event;
  tooltip.style.left = (pageX + 10) + 'px';
  tooltip.style.top = (pageY + 10) + 'px';

  document.body.appendChild(tooltip);
  event.currentTarget._tooltip = tooltip;
}

function hideLinkTooltip(event) {
  if (event.currentTarget._tooltip) {
    event.currentTarget._tooltip.remove();
    event.currentTarget._tooltip = null;
  }
}

// 드래그 동작
function dragStarted(event, d) {
  if (!event.active) simulation.alphaTarget(0.3).restart();
  d.fx = d.x;
  d.fy = d.y;
}

function dragged(event, d) {
  d.fx = event.x;
  d.fy = event.y;
}

function dragEnded(event, d) {
  if (!event.active) simulation.alphaTarget(0);
  d.fx = null;
  d.fy = null;
}

// 뷰 전환
function showGraphView() {
  document.getElementById('graphView').classList.add('active');
  document.getElementById('listView').classList.remove('active');
  document.getElementById('toggleGraphView').classList.add('active');
  document.getElementById('toggleListView').classList.remove('active');
}

function showListView() {
  document.getElementById('graphView').classList.remove('active');
  document.getElementById('listView').classList.add('active');
  document.getElementById('toggleGraphView').classList.remove('active');
  document.getElementById('toggleListView').classList.add('active');
  renderTable();
}

init();
