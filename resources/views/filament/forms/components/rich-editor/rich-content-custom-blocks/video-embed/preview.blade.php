@php
  $label = filled($title ?? null) ? (string) $title : 'Embedded video';
  $url = is_string($url ?? null) ? $url : '';
@endphp
<div style="border:1px solid #e5e7eb;border-radius:12px;padding:16px;background:#f9fafb;">
  <strong style="display:block;margin-bottom:6px;">{{ $label }}</strong>
  @if($url !== '')
    <span style="display:block;font-size:12px;color:#6b7280;word-break:break-all;">{{ $url }}</span>
  @endif
  @if(($embed['type'] ?? null) === 'iframe')
    <span style="display:block;margin-top:8px;font-size:12px;color:#059669;">Ready to embed</span>
  @elseif(($embed['type'] ?? null) === 'video')
    <span style="display:block;margin-top:8px;font-size:12px;color:#059669;">Direct video file</span>
  @else
    <span style="display:block;margin-top:8px;font-size:12px;color:#b45309;">Unsupported URL</span>
  @endif
</div>
