(function(){
  var names=["Home ✿","About ☁","Skills ✧","Projects 🧸","Contact 💌"];
  var main=document.getElementById("main"),nav=document.getElementById("nav"),secs=document.querySelectorAll("section"),bar=document.getElementById("bar");
  names.forEach(function(n,i){var b=document.createElement("button");b.textContent=n;b.onclick=function(){go(i)};nav.appendChild(b)});
  function go(i){main.scrollTo({left:i*main.clientWidth,behavior:"smooth"})}
  document.querySelectorAll("[data-go]").forEach(function(b){b.onclick=function(){go(+b.dataset.go)}});
  function mark(){
    var i=Math.round(main.scrollLeft/main.clientWidth);
    Array.prototype.forEach.call(nav.children,function(b,k){b.classList.toggle("on",k===i)});
    if(secs[i])secs[i].classList.add("in");
    bar.style.width=((i+1)/5*100)+"%";
  }
  main.addEventListener("scroll",function(){clearTimeout(main._t);main._t=setTimeout(mark,40)});
  document.addEventListener("keydown",function(e){
    if(/INPUT|TEXTAREA/.test(e.target.tagName))return;
    var i=Math.round(main.scrollLeft/main.clientWidth);
    if(e.key==="ArrowRight")go(Math.min(4,i+1));if(e.key==="ArrowLeft")go(Math.max(0,i-1));
  });
  mark();

  /* typing effect */
  var roles=["CSE student ✿","aspiring data analyst ✿","SQL & Python learner ✿","curious builder ✿"],ri=0,ci=0,del=false,t=document.getElementById("type");
  (function tick(){
    var w=roles[ri];ci+=del?-1:1;t.textContent=w.slice(0,ci);
    var d=del?40:90;
    if(!del&&ci===w.length){del=true;d=1400}else if(del&&ci===0){del=false;ri=(ri+1)%roles.length;d=300}
    setTimeout(tick,d);
  })();

  /* sparkles */
  var em=["✨","🎀","💖","⭐","🌸","🦋"];
  function spark(x,y,n){
    for(var k=0;k<n;k++){
      var s=document.createElement("span");s.className="sp";s.textContent=em[Math.floor(Math.random()*em.length)];
      s.style.left=x+"px";s.style.top=y+"px";
      s.style.setProperty("--dx",(Math.random()*120-60)+"px");s.style.setProperty("--dy",(Math.random()*-110-10)+"px");
      document.body.appendChild(s);setTimeout(function(el){el.remove()},900,s);
    }
  }
  var last=0;
  document.addEventListener("pointermove",function(e){var n=Date.now();if(n-last>90&&e.pointerType==="mouse"){last=n;spark(e.clientX,e.clientY,1)}});
  document.addEventListener("click",function(e){spark(e.clientX,e.clientY,6)});

  /* quote rotator */
  var qs=["dream it · do it ♡","small steps count ✿","keep showing up ✧","you’ve got this ☁","learn · build · repeat 🌱"],qi=0;
  document.getElementById("scene").onclick=function(){qi=(qi+1)%qs.length;document.getElementById("quote").textContent=qs[qi]};

  /* skill card messages */
  document.querySelectorAll(".skills .card").forEach(function(c){
    c.onclick=function(){document.getElementById("skillnote").textContent="✨ "+c.dataset.m+"!"};
  });

  /* 3D tilt */
  document.querySelectorAll(".card").forEach(function(c){
    c.addEventListener("pointermove",function(e){
      if(e.pointerType!=="mouse")return;
      var r=c.getBoundingClientRect(),x=(e.clientX-r.left)/r.width-.5,y=(e.clientY-r.top)/r.height-.5;
      c.style.transform="perspective(600px) rotateY("+(x*8)+"deg) rotateX("+(-y*8)+"deg) translateY(-3px)";
    });
    c.addEventListener("pointerleave",function(){c.style.transform=""});
  });

  /* project filters */
  var fb=document.querySelectorAll("#filters button");
  fb.forEach(function(b){b.onclick=function(){
    fb.forEach(function(x){x.classList.toggle("on",x===b)});
    document.querySelectorAll(".proj").forEach(function(p){p.classList.toggle("hide",b.dataset.f!=="all"&&p.dataset.c!==b.dataset.f)});
  }});

  /* theme toggle */
  var root=document.documentElement,tb=document.getElementById("theme");
  function setTheme(m){root.setAttribute("data-theme",m);tb.textContent=m==="dark"?"☀️":"🌙";try{localStorage.setItem("mano-theme",m)}catch(e){}}
  var saved=null;try{saved=localStorage.getItem("mano-theme")}catch(e){}
  setTheme(saved||(matchMedia("(prefers-color-scheme:dark)").matches?"dark":"light"));
  tb.onclick=function(){setTheme(root.getAttribute("data-theme")==="dark"?"light":"dark")};

  /* contact form -> saves to MySQL via contact.php */
  var form=document.getElementById("form"),out=document.getElementById("formmsg");
  form.onsubmit=function(e){
    e.preventDefault();
    var r=form.querySelector("button[type=submit]").getBoundingClientRect();
    fetch("contact.php",{method:"POST",body:new FormData(form)})
      .then(function(x){return x.json()})
      .then(function(d){
        out.textContent=d.ok?"Sent with love! I’ll reply soon 💌":(d.error||"Something went wrong");
        out.classList.add("show");
        if(d.ok){spark(r.left+r.width/2,r.top,14);form.reset()}
      })
      .catch(function(){out.textContent="Could not reach the server 😢";out.classList.add("show")});
  };
})();
