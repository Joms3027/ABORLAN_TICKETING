@extends('layouts.admin')

@section('title', 'Home page')

@section('breadcrumb')
  <a href="{{ route('admin.dashboard') }}">Dashboard</a>
  <span aria-hidden="true">/</span>
  <span>Home page</span>
@endsection

@push('head')
  <style>
    .gallery-slide-grid {
      display: grid;
      grid-template-columns: minmax(120px, 200px) 1fr;
      gap: 1rem;
      align-items: start;
    }
    @media (max-width: 640px) {
      .gallery-slide-grid {
        grid-template-columns: 1fr;
      }
      .gallery-slide-grid img {
        max-height: 200px;
        object-fit: cover;
        width: 100%;
      }
    }
  </style>
@endpush

@section('content')
  <div class="page-header">
    <h1>Home page gallery &amp; hero</h1>
    <p>Update the carousel on the public home page. Each slide has a photo and description. Optionally set a wide image for the top hero banner.</p>
  </div>

  <div class="panel">
    <div class="panel-head"><h2>Hero background</h2></div>
    <p class="detail-value muted" style="margin-bottom: 1rem;">The large image behind the headline on the home page. If you do not set a custom image, the first gallery slide’s picture is used.</p>
    @if ($heroImageUrl)
      <div style="margin-bottom: 1rem; border-radius: var(--radius-sm); overflow: hidden; max-width: 720px; border: 1px solid var(--border);">
        <img src="{{ $heroImageUrl }}" alt="Current hero" style="width:100%; height:auto; display:block;" />
      </div>
    @else
      <p class="detail-value muted" style="margin-bottom: 1rem;"><em>No custom hero uploaded—using the first gallery slide.</em></p>
    @endif
    <form method="POST" action="{{ route('admin.homePage.hero') }}" enctype="multipart/form-data" class="form-grid" style="gap: 1rem;">
      @csrf
      <div class="field">
        <label for="hero_image">New hero image (optional)</label>
        <input id="hero_image" type="file" name="hero_image" class="input" accept="image/jpeg,image/png,image/webp,image/gif" />
      </div>
      <div class="form-actions" style="margin-top: 0;">
        <button type="submit" class="btn btn-primary">Upload hero image</button>
      </div>
    </form>
    <form method="POST" action="{{ route('admin.homePage.hero') }}" style="margin-top: 0.75rem;">
      @csrf
      <input type="hidden" name="use_default_hero" value="1" />
      <button type="submit" class="btn btn-secondary btn-sm">Use first gallery image as hero</button>
    </form>
  </div>

  <div class="panel">
    <div class="panel-head"><h2>Add slide</h2></div>
    <form method="POST" action="{{ route('admin.homePage.gallery.store') }}" enctype="multipart/form-data">
      @csrf
      <div class="form-grid two-col">
        <div class="field">
          <label for="new_caption">Description</label>
          <textarea id="new_caption" name="caption" class="textarea" rows="3" required placeholder="Shown under the photo in the carousel">{{ old('caption') }}</textarea>
        </div>
        <div class="field">
          <label for="new_image">Image</label>
          <input id="new_image" type="file" name="image" class="input" accept="image/jpeg,image/png,image/webp,image/gif" required />
          <div class="hint">JPG, PNG, WebP, or GIF. Max ~6 MB.</div>
          @error('image')<div class="field"><div class="hint" style="color: var(--danger);">{{ $message }}</div></div>@enderror
          @error('caption')<div class="hint" style="color: var(--danger);">{{ $message }}</div>@enderror
        </div>
      </div>
      <div class="form-actions">
        <button type="submit" class="btn btn-primary">Add slide</button>
      </div>
    </form>
  </div>

  <div class="panel">
    <div class="panel-head"><h2>Gallery slides ({{ $slides->count() }})</h2></div>
    @if ($slides->isEmpty())
      <p class="empty-state">No slides yet. Add one above.</p>
    @else
      <div style="display: flex; flex-direction: column; gap: 1.25rem;">
        @foreach ($slides as $slide)
          <article style="border: 1px solid var(--border); border-radius: var(--radius); padding: 1rem; background: #faf5ff;">
            <div class="gallery-slide-grid">
              <a href="{{ $slide->publicImageUrl() }}" target="_blank" rel="noopener" style="display:block; border-radius: var(--radius-sm); overflow: hidden; border: 1px solid var(--border);">
                <img src="{{ $slide->publicImageUrl() }}" alt="" style="width:100%; height:auto; display:block;" />
              </a>
              <div>
                <form method="POST" action="{{ route('admin.homePage.gallery.update', $slide) }}" enctype="multipart/form-data">
                  @csrf
                  @method('PATCH')
                  <div class="field" style="margin-bottom: 0.75rem;">
                    <label for="caption_{{ $slide->id }}">Description</label>
                    <textarea id="caption_{{ $slide->id }}" name="caption" class="textarea" rows="3" required>{{ old('caption', $slide->caption) }}</textarea>
                  </div>
                  <div class="field" style="margin-bottom: 0.75rem;">
                    <label for="image_{{ $slide->id }}">Replace image (optional)</label>
                    <input id="image_{{ $slide->id }}" type="file" name="image" class="input" accept="image/jpeg,image/png,image/webp,image/gif" />
                    <div class="hint">{{ $slide->image_path }}</div>
                  </div>
                  <div class="form-actions" style="margin-top: 0;">
                    <button type="submit" class="btn btn-primary btn-sm">Save slide</button>
                  </div>
                </form>
                <form method="POST" action="{{ route('admin.homePage.gallery.destroy', $slide) }}" style="margin-top: 0.75rem;" onsubmit="return confirm('Remove this slide from the home page carousel?');">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-danger btn-sm">Remove slide</button>
                </form>
              </div>
            </div>
          </article>
        @endforeach
      </div>
    @endif
  </div>
@endsection
