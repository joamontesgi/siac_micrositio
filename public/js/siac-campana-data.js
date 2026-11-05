const ACTS=[
 {n:1,t:"Pulso breve de opinión",d:"De 2 a 3 minutos",g:["corta"],p:"Tres preguntas cortas sobre qué entiendes del SIAC, qué duda te queda y qué mejora propones. En papel o por un enlace, en un punto de encuentro del campus.",tags:["Toda la comunidad","Presencial y virtual"]},
 {n:2,t:"Juego de preguntas",d:"De 5 a 8 minutos",g:["corta"],p:"Eliges una tarjeta o giras la ruleta, respondes con un ejemplo cercano y cierras diciendo qué debería mejorar. La actividad invita a compartir experiencias y puntos de vista, sin calificaciones.",tags:["Participación espontánea"]},
 {n:3,t:"Conversaciones en mesas pequeñas",d:"De 45 a 60 minutos",g:["larga"],p:"Grupos de cinco o seis personas que conversan sobre qué funciona y conviene conservar, qué dificulta mejorar y qué debería atender la propuesta.",tags:["Actividad central en encuentros presenciales"]},
 {n:4,t:"Más voces en la conversación",d:"De 35 a 45 minutos",g:["larga"],p:"Un espacio para escuchar a grupos cuya experiencia necesita mayor presencia, como egresados, personal administrativo o estudiantes de posgrado. Cada persona cuenta con un turno inicial para aportar.",tags:["Para ampliar las voces participantes"]},
 {n:5,t:"Círculo de palabra y mapa sencillo",d:"De 45 a 60 minutos",g:["larga"],p:"La dinámica se acuerda con referentes locales. Se habla de dónde se reconoce una buena práctica, dónde hay una dificultad y qué cambio ayudaría.",tags:["Amazonía y Tumaco","Con mediación cultural"]},
 {n:6,t:"Elegir las mejoras más importantes",d:"De 5 a 10 minutos",g:["corta"],p:"Al cerrar otra actividad se reúnen hasta cinco propuestas. Cada persona elige dos y explica una de sus elecciones. Las necesidades minoritarias se conservan.",tags:["Dentro del cierre"]},
 {n:7,t:"Tu aporte al SIAC",d:"De 3 a 5 minutos",g:["corta","sin"],p:"Comparte qué conservarías o mejorarías del SIAC a través de una pregunta abierta. La metodología contempla un buzón físico y un canal digital durante el periodo de participación.",tags:["Participación sin asistir","Del 1 de octubre al 2 de diciembre"]},
 {n:8,t:"Una imagen y una propuesta",d:"De 5 a 10 minutos",g:["corta","sin"],p:"Envías una foto, un dibujo o una descripción con dos frases: qué situación muestra y qué mejora propone. Sin personas identificables ni datos personales.",tags:["Opcional","Participación sin asistir"]},
 {n:9,t:"Un caso para ponerse en el lugar de otros",d:"De 35 a 45 minutos",g:["larga"],p:"Un caso con roles: una evaluación detectó una dificultad y nadie sabe quién debe responder. El grupo propone una solución y señala qué necesita aclarar el SIAC.",tags:["Grupos de 6 a 10","Puede reemplazar las mesas"]},
 {n:10,t:"Entrevista para profundizar",d:"De 20 a 30 minutos",g:["larga"],p:"Una conversación de hasta tres preguntas para profundizar en aspectos que requieren más información. Se utiliza cuando ayuda a comprender mejor la propuesta.",tags:["Puntual"]}];

const FAQ=[
  [
    "¿Cómo se relacionan el SIAC y la acreditación?",
    "El propósito del SIAC es fortalecer el mejoramiento continuo de la Universidad. La acreditación es uno de los procesos con los que se articula. Su alcance también incluye la evaluación, la participación, la información y el seguimiento a las mejoras."
  ],
  [
    "¿Qué hace diferente esta participación?",
    "La metodología combina conversaciones, actividades breves y una pregunta abierta. Además, prevé registrar, analizar y responder los aportes. Puedes elegir el espacio que mejor se ajuste a tu tiempo y evitar repetir una misma idea en varios canales."
  ],
  [
    "¿Qué alcance tiene mi participación?",
    "Esta fase permite conocer la propuesta y aportar a su revisión. Los aportes se registran, analizan y responden, y contribuyen a las decisiones institucionales. La aprobación corresponde a los órganos de gobierno universitario competentes. La propuesta contempla ajustes al Acuerdo 151 de 2014 y al marco de Claustros y Colegiaturas."
  ],
  [
    "¿Puedo aportar si no asisto al encuentro?",
    "Sí. La metodología contempla el canal «Tu aporte al SIAC», con una pregunta abierta y sin registro obligatorio, entre el 1 de octubre y el 2 de diciembre. También prevé un buzón físico en el punto de encuentro. El formulario de esta maqueta es una demostración y no envía ni guarda aportes."
  ],
  [
    "¿Cómo se protege mi identidad?",
    "La metodología prevé identificar los aportes con un código para facilitar su seguimiento sin publicar nombres. La publicación de nombres requiere autorización. Para las actividades con imágenes se solicita evitar personas identificables y datos personales."
  ],
  [
    "¿Cómo se amplía la participación?",
    "Si la participación es baja, la metodología prevé informar las cifras y su contexto, ampliar el canal cinco días y buscar otras formas de escuchar a la comunidad: tarjetas en papel, apoyo de multiplicadores y conversaciones con grupos que aún no estén representados."
  ],
  [
    "¿Qué ocurre si se aplaza un encuentro?",
    "Se ajusta la programación y se mantiene la información necesaria para orientar a la comunidad. La metodología fija el 18 de diciembre como fecha límite prevista para la publicación de resultados."
  ],
  [
    "¿Dónde podré consultar la propuesta y sus cambios?",
    "La versión definitiva del micrositio deberá ofrecer acceso al documento vigente, su fecha y su registro de cambios. Esta maqueta presenta las referencias documentales; todavía no incorpora los enlaces de consulta."
  ],
  [
    "¿Cómo ayuda el ciclo ACTÚA a mejorar?",
    "Propone cinco pasos conectados: Apreciar lo que funciona, Comprender las causas y experiencias, Trazar las mejoras, Unir a quienes las harán posibles y Aprender de los resultados. Lo aprendido permite iniciar un nuevo ciclo de mejora."
  ]
];

/* ---- actividades ---- */
const actsEl=document.getElementById("acts");
function renderActs(f){
  actsEl.innerHTML=ACTS.filter(a=>f==="all"||a.g.includes(f)).map(a=>`
   <article class="act"><span class="n">${a.n}</span><div>
     <h3>${a.t}</h3><p>${a.p}</p>
     <div class="meta"><span class="tag on">${a.d}</span>${a.tags.map(t=>`<span class="tag">${t}</span>`).join("")}</div>
   </div></article>`).join("");
}
renderActs("all");

/* ---- filtros ---- */
const fbox=document.getElementById("f-act");
fbox.addEventListener("click",e=>{
  const b=e.target.closest("button"); if(!b) return;
  fbox.querySelectorAll("button").forEach(x=>x.setAttribute("aria-pressed",String(x===b)));
  renderActs(b.dataset.f);
});

/* ---- preguntas ---- */
document.getElementById("faq").innerHTML=FAQ.map(([q,a])=>`<details><summary>${q}</summary><div class="ans">${a}</div></details>`).join("");

/* ---- formulario ---- */
document.getElementById("aporte-form").addEventListener("submit",e=>{
  e.preventDefault();
  const q=document.getElementById("q");
  if(!q.value.trim()){q.focus();return;}
  document.getElementById("rid").textContent=`APT-UNAL-${Math.floor(Math.random()*900)+100}`;
  document.getElementById("rdate").textContent="Se analizaría junto con aportes del mismo tema, conservando su código. La decisión y su explicación se publicarían en la ficha de resultados del encuentro correspondiente, dentro del plazo previsto en la metodología.";
  const r=document.getElementById("receipt"); r.hidden=false;
  r.scrollIntoView({behavior:"smooth",block:"nearest"});
});

/* ---- pestañas ---- */
const TABS=["siac","calidad","normatividad","sisaica","participa","devolucion","preguntas"];
const tabEls=TABS.map(n=>document.getElementById("t-"+n));
function show(name,push){
  if(!TABS.includes(name)) name="siac";
  TABS.forEach(n=>{
    const sel=n===name;
    document.getElementById("t-"+n).setAttribute("aria-selected",String(sel));
    document.getElementById("p-"+n).hidden=!sel;
  });
  if(push && location.hash.slice(1)!==name) location.hash=name;
}
tabEls.forEach((b,i)=>{
  b.addEventListener("click",()=>{show(TABS[i],true);window.scrollTo({top:0,behavior:"smooth"});});
  b.addEventListener("keydown",e=>{
    const d=e.key==="ArrowRight"?1:e.key==="ArrowLeft"?-1:0;
    if(!d) return;
    e.preventDefault();
    const j=(i+d+TABS.length)%TABS.length;
    tabEls[j].focus(); show(TABS[j],true);
  });
});
document.querySelectorAll("[data-go]").forEach(a=>{
  a.addEventListener("click",e=>{e.preventDefault();show(a.dataset.go,true);window.scrollTo({top:0,behavior:"smooth"});});
});
window.addEventListener("hashchange",()=>show(location.hash.slice(1),false));
show(location.hash.slice(1)||"siac",false);
