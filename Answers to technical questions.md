# Answers to Technical Questions

## 1. How long did you spend?

I would describe the effort as roughly 3 to 4 days of focused work. Most of that time went into responsive implementation, keeping the PHP structure clean enough to scale, matching the provided UI closely across breakpoints, and making sure the CRUD flow, validation, and testing coverage felt production-ready rather than assignment-level.

If I had more time, I would add drag-and-drop slide ordering, image cropping for admin uploads, automated feature tests around the CRUD workflows, and a small JSON API layer to separate the admin and frontend rendering concerns even further.

## 2. How would you track down a performance issue in production? Have you ever had to do this?

My approach starts with narrowing the bottleneck before changing code. I usually begin with monitoring and logs to see whether the issue is CPU, memory, network, database time, or a frontend rendering problem. From there I would check application logs, web server logs, slow query logs, and APM traces from tools like New Relic, Datadog, or Blackfire if available.

For backend issues, I look at query execution plans, missing indexes, N+1 patterns, repeated filesystem access, and expensive loops around database results. For frontend issues, I use Lighthouse, browser Performance and Network tabs, and request waterfalls to identify oversized assets, layout shifts, blocking scripts, or unnecessary rerenders. If caching is involved, I verify cache hit rates and invalidation behavior before assuming the origin code is at fault.

Yes, I have had to do this in production. One example was a slow reporting screen where the main issue turned out to be a combination of missing composite indexes and repeated aggregation queries inside a loop. We used slow query logs and profiling to identify the worst offenders, added the right indexes, consolidated the queries, and reduced page load time significantly without changing the user-facing workflow.

## 3. Please describe yourself using JSON

```json
{
  "name": "Mathan Raj",
  "role": "Software Engineer",
  "experience": "2.7+ years",
  "skills": [
    "PHP",
    "MySQL",
    "Laravel",
    "JavaScript",
    "React",
    "WordPress",
    "Bootstrap"
  ],
  "strengths": [
    "Problem Solving",
    "Scalable Architecture",
    "API Development",
    "Responsive UI Engineering",
    "Production Debugging"
  ],
  "work_style": {
    "approach": "Clean, maintainable, and performance-aware",
    "focus": [
      "User Experience",
      "Code Quality",
      "Security",
      "Scalability"
    ]
  }
}
```
