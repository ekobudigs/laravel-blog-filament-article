    <div class="flex justify-between mb-4 gap-3">
        <div class="w-12 h-12 flex items-center justify-center bg-gray-200 rounded-full">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
              </svg>
        </div>
        <div class="flex-1">
            <div>
                <a href="#" class="font-semibold text-indigo-500">
                    {{ $comment->user->name }}
                </a>
                - <span class="text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
            </div>
            @if($editing)
            <livewire:comment-create :comment-model="$comment"/>
            @else
            <div class="text-gray-700">
                {{ $comment->comment }}
            </div>
            @endif
           
            <div>
                <a wire:click.prevent="startReplay" href="#" class="text-sm text-indigo-600 mr-3">Reply</a>

                @if(\Illuminate\Support\Facades\Auth::id() == $comment->user_id)
                <a wire:click.prevent="startCommentEdit" href="#" class="text-sm text-blue-600 mr-3">Edit</a>
                <a wire:click.prevent="deleteComment" href="#" class="text-sm text-red-600">Delete</a> 
               
                @endif
              
            </div>
            @if($replying)
                
            <livewire:comment-create :post="$comment->post" :parent-comment="$comment" /> 
            @endif

            @if(!empty($comment->comments->count()))
                
           
                <div class="mt-4">
                                 
    @foreach ($comment->comments as $childComment)
    <livewire:comment-item :comment="$childComment" wire:key="comment-{{ $childComment->id }}" />
@endforeach
                </div>
                @endif

        </div>
       
    </div>
   
  

